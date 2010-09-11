<?php

class Trollweb_SilverFramework_Block_Adminhtml_Cmslinks extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_cmslinks';
		$this->_blockGroup = 'silverframework';
		$this->_headerText = Mage::helper('silverframework')->__('Manage horisontal menu links');
		$this->_addButtonLabel = Mage::helper('silverframework')->__('Add Link');
		parent::__construct();
	}
}