<?php

class Trollweb_SilverFramework_Block_Adminhtml_Cmslinks_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('cmslinks_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('silverframework')->__('CMS link information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
			'label'     => Mage::helper('silverframework')->__('Item Information'),
			'title'     => Mage::helper('silverframework')->__('Item Information'),
			'content'   => $this->getLayout()->createBlock('silverframework/adminhtml_cmslinks_edit_tab_form')->toHtml(),
		));
		return parent::_beforeToHtml();
	}
}