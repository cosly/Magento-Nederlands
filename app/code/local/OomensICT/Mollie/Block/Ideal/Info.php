<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Info Block
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */

class OomensICT_Mollie_Block_Ideal_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mollie/ideal/info.phtml');
    }

    public function toPdf()
    {
        $this->setTemplate('mollie/ideal/pdf/info.phtml');
        return $this->toHtml();
    }

    /**
     * Gets Bank Title from Payment Attribute
     *
     * @return string
     */
    public function getBankTitle()
    {
        if ($this->getInfo() instanceof Mage_Sales_Model_Quote_Payment) {
            $banksList = unserialize($this->getInfo()->getMollieBankList());
            return $banksList[$this->getInfo()->getMollieBankId()];
        } else {
        	  return $this->getInfo()->getMollieBankTitle();
        }
    }

}