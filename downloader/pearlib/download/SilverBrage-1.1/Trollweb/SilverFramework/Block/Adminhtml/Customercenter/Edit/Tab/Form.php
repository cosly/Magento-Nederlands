<?php

class Trollweb_SilverFramework_Block_Adminhtml_Customercenter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('customercenter_form', array('legend'=>Mage::helper('silverframework')->__('Item information')));
		
		$fieldset->addField('name', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Link Name'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'name',
		));

		$fieldset->addField('link', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Link URL'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'link',
		));

		$fieldset->addField('sort', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Sorting'),
			'class'     => '',
			'required'  => false,
			'name'      => 'sort',
		));		
		
		$fieldset->addField('parent_id', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Parent ID'),
			'class'     => '',
			'required'  => false,
			'name'      => 'parent_id',
		));		
		
		if ( Mage::getSingleton('adminhtml/session')->getCustomercenterData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getCustomercenterData());
			Mage::getSingleton('adminhtml/session')->setCustomercenterData(null);
		} elseif ( Mage::registry('customercenter_data') ) {
			$form->setValues(Mage::registry('customercenter_data')->getData());
		}
			
		return parent::_prepareForm();
	}
}