<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL data helper
 *
 * @category    Mage
 * @package     Mage_Ideal
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class OomensICT_Mollie_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function encrypt($token)
    {
        return bin2hex(base64_decode(Mage::helper('core')->encrypt($token)));
    }

    public function decrypt($token)
    {
        return Mage::helper('core')->decrypt(base64_encode(pack('H*', $token)));
    }
}
