<?php


class Trollweb_SilverFramework_Model_Mysql4_Tabs extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('silverframework/tabs', 'tab_id');
	}
}