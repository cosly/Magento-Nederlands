<?php

class Trollweb_SilverFramework_Block_Adminhtml_Customercenter extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_customercenter';
		$this->_blockGroup = 'silverframework';
		$this->_headerText = Mage::helper('silverframework')->__('Manage customercenter');
		$this->_addButtonLabel = Mage::helper('silverframework')->__('Add Link');
		parent::__construct();
	}
}