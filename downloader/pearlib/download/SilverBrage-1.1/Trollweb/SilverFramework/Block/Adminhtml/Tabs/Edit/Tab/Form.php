<?php

class Trollweb_SilverFramework_Block_Adminhtml_Tabs_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('tabs_form', array('legend'=>Mage::helper('silverframework')->__('Item information')));
		
		$fieldset->addField('name', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Tab name'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'name',
		));

		$fieldset->addField('type', 'select', array(
			'label'     => Mage::helper('silverframework')->__('Type'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'type',
			'values'	=> array(
				array('label' => 'Child block', 'value' => 'child'),
				array('label' => 'Product attribute', 'value' => 'attribute'),
				array('label' => 'Static content', 'value' => 'content'),
			)
		));

		$fieldset->addField('content', 'textarea', array(
			'label'     => Mage::helper('silverframework')->__('Content'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'content',
		));
		
		$fieldset->addField('sort', 'text', array(
			'label'     => Mage::helper('silverframework')->__('Sorting'),
			'class'     => '',
			'required'  => false,
			'name'      => 'sort',
		));		
		
		if ( Mage::getSingleton('adminhtml/session')->getTabsData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getTabsData());
			Mage::getSingleton('adminhtml/session')->setTabsData(null);
		} elseif ( Mage::registry('tabs_data') ) {
			$form->setValues(Mage::registry('tabs_data')->getData());
		}
			
		return parent::_prepareForm();
	}
}
