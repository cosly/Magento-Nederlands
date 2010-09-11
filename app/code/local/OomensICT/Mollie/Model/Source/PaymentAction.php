<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */


/**
 *
 * Mollie Payment Action Dropdown source
 *
 * @author      V.J. Oomens
 */
class OomensICT_Mollie_Model_Source_PaymentAction
{
    public function toOptionArray()
    {
        return array(
            array('value' => OomensICT_Mollie_Model_Ideal::PAYMENT_TYPE_SALE, 'label' => Mage::helper('mollie')->__('Sale')),
        );
    }
}