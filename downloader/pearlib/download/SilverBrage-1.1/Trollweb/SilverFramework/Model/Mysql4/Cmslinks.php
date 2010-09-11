<?php


class Trollweb_SilverFramework_Model_Mysql4_Cmslinks extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('silverframework/cmslinks', 'cmslink_id');
	}
}