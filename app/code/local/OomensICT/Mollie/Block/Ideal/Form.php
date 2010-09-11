<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Form Block to show issuer list
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */

class OomensICT_Mollie_Block_Ideal_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('mollie/ideal/form.phtml');
        parent::_construct();
    }

    /**
     * Return array that contains issuer list
     *
     * @return array
     */
    public function getBanksList()
    {
        return $this->getMethod()->getBanksList();
    }
}