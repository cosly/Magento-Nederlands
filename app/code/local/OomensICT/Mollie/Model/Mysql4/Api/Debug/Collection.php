<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie Debug Resource Collection
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @name        OomensICT_Mollie_Model_Mysql4_Api_Debug_Collection
 * @author      Victor Oomens
 */
class OomensICT_Mollie_Model_Mysql4_Api_Debug_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('mollie/api_debug');
    }
}