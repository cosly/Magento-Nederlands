<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Failure Response page from Mollie iDEAL
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */
 
class OomensICT_Mollie_Block_Ideal_Failure extends Mage_Core_Block_Template
{
    /**
     *  Returns error from session and clears it
     *
     *  @return	  string
     */
    public function getErrorMessage ()
    {
        $error = Mage::getSingleton('checkout/session')->getMollieErrorMessage();
        Mage::getSingleton('checkout/session')->unsMollieErrorMessage();
        return $error;
    }

    /**
     * Get continue shopping url
     */
    public function getContinueShoppingUrl()
    {
        return Mage::getUrl('checkout/cart');
    }
}