<?php


class Trollweb_SilverFramework_Model_Tabs extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('silverframework/tabs');
	}
	
	public function foobar()
	{
		echo 'foobar in model!';
	}
	
}