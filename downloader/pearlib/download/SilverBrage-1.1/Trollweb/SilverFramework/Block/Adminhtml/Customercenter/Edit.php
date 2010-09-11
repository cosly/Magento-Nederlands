<?php

class Trollweb_SilverFramework_Block_Adminhtml_Customercenter_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'silverframework';
		$this->_controller = 'adminhtml_customercenter';
		$this->_updateButton('save', 'label', Mage::helper('silverframework')->__('Save Link'));
		$this->_updateButton('delete', 'label', Mage::helper('silverframework')->__('Delete Link'));
	}
	
	public function getHeaderText()
	{
		if( Mage::registry('silverframework_data') && Mage::registry('silverframework_data')->getId() ) {
			return Mage::helper('silverframework')->__("Edit Link '%s'", $this->htmlEscape(Mage::registry('silverframework_data')->getTitle()));
		} else {
			return Mage::helper('silverframework')->__('Add Link');
		}
	}
}