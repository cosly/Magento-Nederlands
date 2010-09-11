<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * iDEAL API Debug Resource
 *
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @name       OomensICT_Mollie_Model_Mysql4_Api_Debug
 * @author     Victor Oomens
 */

class OomensICT_Mollie_Model_Mysql4_Api_Debug extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('mollie/api_debug', 'debug_id');
    }
}