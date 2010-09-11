<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Api Model
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */

class OomensICT_Mollie_Model_Api_Ideal extends Varien_Object
{
    const     MIN_TRANS_AMOUNT = 118;

    protected $partner_id      = null;
    protected $testmode        = false;

    protected $bank_id         = null;
    protected $amount          = 0;
    protected $description     = null;
    protected $return_url      = null;
    protected $report_url      = null;

    protected $bank_url        = null;
    protected $transaction_id  = null;
    protected $paid_status     = false;
    protected $consumer_info   = array();

    public function __construct () {
      $this->partner_id = $this->getConfigData('partner_id');
      
      if ($this->getConfigData('test_flag') == 1) $this->testmode = true;
      
      if ($this->getConfigData('description'))
      		$this->description = $this->getConfigData('description');
      else
     			$this->description = Mage::app()->getStore()->getName() . ' payment';
    }
    
    /**
     * Getting config parametrs
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfigData($key, $default=false)
    {
        if (!$this->hasData($key)) {
             $value = Mage::getStoreConfig('payment/mollie_ideal/'.$key);
             if (is_null($value) || false===$value) {
                 $value = $default;
             }
            $this->setData($key, $value);
        }
        return $this->getData($key);
    }

    public function getBanks () {
      $banks_xml = $this->_sendRequest('www.mollie.nl', '/xml/ideal/', 'a=banklist' . (($this->testmode) ? '&testmode=true' : ''));

      if (empty($banks_xml)) {
        return false;
      }

      $banks_object = $this->_XMLtoObject($banks_xml);

      if (!$banks_object) {
        return false;
      }

       $banks_array = array();
       foreach ($banks_object->bank as $bank) {
         $banks_array["{$bank->bank_id}"] = "{$bank->bank_name}";
       }

       return $banks_array;
    }

    public function createPayment ($bank_id, $amount, $return_url, $report_url) {
    	
      if (!$this->setAmount($amount) or
      		!$this->setBankId($bank_id) or
          !$this->setReturnUrl($return_url) or
          !$this->setReportUrl($report_url)) {
        echo $amount, $bank_id, $return_url, $report_url;  	
	      return false;
      }


      $create_xml = $this->_sendRequest(
                      'www.mollie.nl',
                      '/xml/ideal/',
                      'a=fetch' .
                        '&partnerid=' .   urlencode($this->getPartnerId()) .
                        '&bank_id=' .     urlencode($this->getBankId()) .
                        '&amount=' .      urlencode($this->getAmount()) .
                        '&reporturl=' .   urlencode($this->getReportUrl()) .
                        '&description=' . urlencode($this->getDescription()) .
                        '&returnurl=' .   urlencode($this->getReturnUrl()));
                        
                        
      if (empty($create_xml)) {
        return false;
      }

      $create_object = $this->_XMLtoObject($create_xml);

      if (!$create_object) {
        return false;
      }
      
      $this->transaction_id = $create_object->order->transaction_id;
      $this->bank_url       = $create_object->order->URL;

      return $this;
    }

    public function checkPayment ($transaction_id) {
      if (!$this->setTransactionId($transaction_id)) {
        return false;
      }

      $check_xml = $this->_sendRequest(
                     'www.mollie.nl',
                     '/xml/ideal/',
                     'a=check' .
                       '&partnerid=' .      urlencode($this->getPartnerId()) .
                       '&transaction_id=' . urlencode($this->getTransactionId()) .
                       (($this->testmode) ? '&testmode=true' : ''));

      if (empty($check_xml)) {
        return false;
      }

      $check_object = $this->_XMLtoObject($check_xml);

      if (!$check_object) {
        return false;
      }

      $this->paid_status   = ($check_object->order->payed == 'true');
      $this->amount        = $check_object->order->amount;
      $this->consumer_info = (isset($check_object->order->consumer)) ? (array) $check_object->order->consumer : array();

      return $this;
    }

/*
  PROTECTED FUNCTIONS
*/

    protected function _sendRequest ($host, $path, $data) {
      $fp = @fsockopen($host, 80);
      $buf = '';
      if ($fp) {
        @fputs($fp, "POST $path HTTP/1.0\n");
        @fputs($fp, "Host: $host\n");
        @fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        @fputs($fp, "Content-length: " . strlen($data) . "\n");
        @fputs($fp, "Connection: close\n\n");
        @fputs($fp, $data);

        while (!feof($fp)) {
          $buf .= fgets($fp, 128);
        }
        fclose($fp);
      }

      if (empty($buf)) {
        return false;
      }
      else {
        list($headers, $body) = preg_split("/(\r?\n){2}/", $buf, 2);

        return $body;
      }
    }

    protected function _XMLtoObject ($xml) {
      try {
        $xml_object = new SimpleXMLElement($xml);
        if ($xml_object == false) {
          return false;
        }
      }
      catch (Exception $e) {
        return false;
      }

      return $xml_object;
    }

/*
  SET AND GET FUNCTIONS
*/

    public function setPartnerId ($partner_id) {
      if (!is_numeric($partner_id)) {
        return false;
      }

      return ($this->partner_id = $partner_id);
    }

    public function getPartnerId () {
      return $this->partner_id;
    }

    public function setTestmode () {
      return ($this->testmode = true);
    }

    public function setBankId ($bank_id) {
      if (!is_numeric($bank_id)) {
        return false;
      }

      return ($this->bank_id = $bank_id);
    }

    public function getBankId () {
      return $this->bank_id;
    }

    public function setAmount ($amount) {
      if (!ereg('^[0-9]{0,}$', $amount)) {
        return false;
      }
      if (self::MIN_TRANS_AMOUNT > $amount) {
        return false;
      }

      return ($this->amount = $amount);
    }

    public function getAmount () {
      return $this->amount;
    }

    public function setDescription ($description) {
      $description = substr($description, 0, 29);

      return ($this->description = $description);
    }

    public function getDescription () {
      return $this->description;
    }

    public function setReturnURL ($return_url) {
      if (!preg_match('|(\w+)://([^/:]+)(:\d+)?/(.*)|', $return_url)) {
        return false;
      }

      return ($this->return_url = $return_url);
    }

    public function getReturnURL () {
      return $this->return_url;
    }

    public function setReportURL ($report_url) {
      if (!preg_match('|(\w+)://([^/:]+)(:\d+)?/(.*)|', $report_url)) {
        return false;
      }

      return ($this->report_url = $report_url);
    }

    public function getReportURL () {
      return $this->report_url;
    }

    public function setTransactionId ($transaction_id) {
      if (empty($transaction_id)) {
        return false;
      }

      return ($this->transaction_id = $transaction_id);
    }

    public function getTransactionId () {
      return $this->transaction_id;
    }

    public function getBankURL () {
      return $this->bank_url;
    }

    public function getPaidStatus () {
      return $this->paid_status;
    }

    public function getConsumerInfo () {
      return $this->consumer_info;
    }

}
