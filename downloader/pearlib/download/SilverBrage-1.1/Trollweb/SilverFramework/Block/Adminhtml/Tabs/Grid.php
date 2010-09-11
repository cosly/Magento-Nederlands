<?php

class Trollweb_SilverFramework_Block_Adminhtml_Tabs_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('TabsGrid');
		$this->setDefaultSort('sort');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('silverframework/tabs')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('tab_id', array(
			'header'    => Mage::helper('silverframework')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'tab_id',
		));
		
		$this->addColumn('name', array(
			'header'    => Mage::helper('silverframework')->__('Tab name'),
			'align'     =>'left',
			'index'     => 'name',
		));
		
		$this->addColumn('type', array(
			'header'    => Mage::helper('silverframework')->__('Type'),
			'align'     =>'left',
			'index'     => 'type',
			'type'		=> 'options',
			'options'	=> array(
				'child' => 'Child block',
				'attribute' => 'Product attribute',
				'content' => 'Static content',
			)
		));		
		
		$this->addColumn('sort', array(
			'header'    => Mage::helper('silverframework')->__('Sorting'),
			'align'     =>'left',
			'index'     => 'sort',
		));				
		
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}