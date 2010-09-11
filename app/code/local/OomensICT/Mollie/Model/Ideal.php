<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Checkout Model
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */

class OomensICT_Mollie_Model_Ideal extends Mage_Payment_Model_Method_Abstract
{
    const PAYMENT_TYPE_SALE = 'SALE';
    
    protected $_code  = 'mollie_ideal';
    protected $_formBlockType = 'mollie/ideal_form';
    protected $_infoBlockType = 'mollie/ideal_info';
    protected $_allowCurrencyCode = array('EUR');
    protected $_banksList = null;

    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    public function canUseCheckout()
    {
        if ($this->getBanksList() && parent::canUseCheckout()) {
            return true;
        } else {
            return false;
        }
    }

    public function getOrderPlaceRedirectUrl()
    {
          return Mage::getUrl('mollie/ideal/redirect', array('_secure' => true));
    }

    /**
     * Get Mollie iDEAL API Model
     *
     * @return OomensICT_Mollie_Model_Api_Ideal
     */
    public function getApi()
    {
        return Mage::getSingleton('mollie/api_ideal');
    }

    public function getBanksList()
    {
        if ($this->_banksList == null) {
            $response = $this->getApi()->getBanks();
            if ($response) {
                $this->_banksList = $response;
                return $this->_banksList;
            } else {
                $this->_banksList = null;
                $this->setError('Unable to retrieve banks list.');
                return false;
            }
        } else {
            $this->getInfoInstance()
                ->setMollieBankList(serialize($this->_banksList))
                ->save();
            return $this->_banksList;
        }
    }

    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setMollieBankId($data->getMollieBankId());

        return $this;
    }

    /**
     * Prepare info instance for save
     *
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function prepareSave()
    {
        $info = $this->getInfoInstance();

        $this->getQuote()->getPayment()->setAdditionalInformation(
	        array(
		        'mollie_bank_id' => $info->getMollieBankId(),
		        'mollie_bank_list' => $info->getMollieBankList()
	        )
        );
        return $this;
    }

    /**
     * validate the currency code is avaialable to use for Mollie iDEAL or not
     *
     * @return bool
     */
    public function validate()
    {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!in_array($currency_code,$this->_allowCurrencyCode)) {
            Mage::throwException(Mage::helper('mollie')->__('Selected currency code ('.$currency_code.') is not compatible with iDEAL'));
        }
        return $this;
    }

    /**
     * Preapre and send transaction request
     *
     * @param Mage_Sales_Model_Order $order
     * @param string $bankId
     * @return boolean
     */
    public function createPayment(Mage_Sales_Model_Order $order, $bankId) 
    {
    		$reportUrl = $this->getReportUrl();
    		$returnUrl = $this->getReturnUrl();
        return $this->getApi()->createPayment($bankId, intval($order->getBaseGrandTotal() * 100 + 0.5), $returnUrl, $reportUrl); 
    }
    
    public function checkPayment($transactionId)
    {
        //when verified need to convert order into invoice
        $order = Mage::getModel('sales/order');
        $order->loadByAttribute('mollie_transaction_id', $transactionId);
        
        $response = $this->getApi()->checkPayment($transactionId);

        if ($response->getPaidStatus() == true) {
            if (!$order->getId()) {
                /*
                * need to have logic when there is no order with the transaction id from mollie
                */
            } else {
   				      $order->setMolliePaidStatus(1);
   				      $orderAmount = intval($order->getBaseGrandTotal() * 100 + 0.5);
   				      $idealAmount = intval($response->getAmount());
                if ($idealAmount!==$orderAmount) {
                    //when grand total does not equal, need to have some logic to take care
                    $order->addStatusToHistory(
                        $order->getStatus(),//continue setting current order status
                        Mage::helper('mollie')->__('Order total amount does not match ideal gross total amount')
                    );
                    $order->save();
                } else {
                    /*
                    //quote id
                    $quote_id = $order->getQuoteId();
                    //the customer close the browser or going back after submitting payment
                    //so the quote is still in session and need to clear the session
                    //and send email
                    if ($this->getQuote() && $this->getQuote()->getId()==$quote_id) {
                        $this->getCheckout()->clear();
                        $order->sendNewOrderEmail();
                    }
                    */

                    // get from config order status to be set
                    $newOrderStatus = $this->getConfigData('order_status', $order->getStoreId());
                    if (empty($newOrderStatus)) {
                        $newOrderStatus = $order->getStatus();
                    }

                    /*
                    if payer_status=verified ==> transaction in sale mode
                    if transactin in sale mode, we need to create an invoice
                    otherwise transaction in authorization mode
                    */
										if (!$order->canInvoice()) {
										   //when order cannot create invoice, need to have some logic to take care
										   $order->addStatusToHistory(
										        $order->getStatus(), // keep order status/state
										        Mage::helper('mollie')->__('Error in creating an invoice', true),
										        $notified = true
										   );
										} else {
										   //need to save transaction id
										   $order->getPayment()->setTransactionId($transactionId);
										   //need to convert from order into invoice
										   $invoice = $order->prepareInvoice();
										   $invoice->register()->capture();
										   Mage::getModel('core/resource_transaction')
										       ->addObject($invoice)
										       ->addObject($invoice->getOrder())
										       ->save();
										   $order->setState(
										       Mage_Sales_Model_Order::STATE_PROCESSING, $newOrderStatus,
										       Mage::helper('mollie')->__('Invoice #%s created', $invoice->getIncrementId()),
										       $notified = true
										   );
										}
                    $order->save();
                    $order->sendNewOrderEmail();
                    $order->setEmailSent(true);
                    $order->save();
                }
            }
        } else {
            if (!$order->getId()) {
                /*
                * need to have logic when there is no order with the transaction id from ideal
                */
            } else {
   				      $order->setMolliePaidStatus(0);
   				      /*
                $order->addStatusToHistory(
                    $order->getStatus(),//continue setting current order status
                    Mage::helper('mollie')->__('Mollie ideal payment canceled.')
                );
                */
                $order->save();
            }
        }
    }

		/*
    public function capture(Varien_Object $payment, $amount)
    {
        $payment->setStatus(self::STATUS_APPROVED)
            ->setLastTransId($this->getTransactionId());

        return $this;
    }
    */

    public function cancel(Varien_Object $payment)
    {
        $payment->setStatus(self::STATUS_DECLINED);

        return $this;
    }

    /**
     * Get debug flag
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->getConfigData('debug_flag');
    }
    
    public function getReturnUrl() {
        return Mage::getUrl('mollie/ideal/return');
    }
    
    public function getReportUrl() {
        return Mage::getUrl('mollie/ideal/report');
    }

    public function isInitializeNeeded()
    {
        return true;
    }

    public function initialize($paymentAction, $stateObject)
    {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_mollie');
        $stateObject->setIsNotified(false);
    }

    public function canUseForMultishipping()
    {
        return false;
    }

    public function canCapture()
    {
        return true;
    }

    /**
     * Check partial capture availability
     *
     * @return bool
     */
    public function canCapturePartial()
    {
        return true;
    }

} 
