<?php

class Trollweb_SilverFramework_Block_Adminhtml_Tabs extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_tabs';
		$this->_blockGroup = 'silverframework';
		$this->_headerText = Mage::helper('silverframework')->__('Manage tabs');
		$this->_addButtonLabel = Mage::helper('silverframework')->__('Add tab');
		parent::__construct();
	}
}