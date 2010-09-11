<?php

class Trollweb_SilverFramework_Block_Adminhtml_Customercenter_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('CustomerCenterGrid');
		$this->setDefaultSort('customercenter_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('silverframework/customercenter')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('customercenter_id', array(
			'header'    => Mage::helper('silverframework')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'customercenter_id',
		));
		
		$this->addColumn('name', array(
			'header'    => Mage::helper('silverframework')->__('Link Name'),
			'align'     =>'left',
			'index'     => 'name',
		));
		
		$this->addColumn('link', array(
			'header'    => Mage::helper('silverframework')->__('Link URL'),
			'align'     =>'left',
			'index'     => 'link',
		));		
		
		$this->addColumn('sort', array(
			'header'    => Mage::helper('silverframework')->__('Sorting'),
			'align'     =>'left',
			'index'     => 'sort',
		));				
		
		$this->addColumn('parent_id', array(
			'header'    => Mage::helper('silverframework')->__('Parent ID'),
			'align'     =>'left',
			'index'     => 'parent_id',
		));				
		
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}