<?php

class Trollweb_SilverFramework_Adminhtml_CmslinksController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('cmslinks/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		return $this;
	}   
	
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('silverframework/adminhtml_cmslinks'));	
		$this->renderLayout();
	}
	public function editAction()
	{
		$customercenterId     = $this->getRequest()->getParam('id');
		$customercenterModel  = Mage::getModel('silverframework/cmslinks')->load($customercenterId);
		
		if ($customercenterModel->getId() || $customercenterId == 0) {
			Mage::register('cmslinks_data', $customercenterModel);
			
			
			
			$this->loadLayout();
			$this->_setActiveMenu('cmslinks/items');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('silverframework/adminhtml_cmslinks_edit'))
				->_addLeft($this->getLayout()->createBlock('silverframework/adminhtml_cmslinks_edit_tabs'));
				
			$this->renderLayout();

		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('silverframework')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function newAction()
	{
		$this->_forward('edit');
	}
	public function saveAction()
	{
		if ( $this->getRequest()->getPost() ) {
			try {
				$postData = $this->getRequest()->getPost();
				$customercenterModel = Mage::getModel('silverframework/cmslinks');
				$customercenterModel->setId($this->getRequest()->getParam('id'))
					->setName($postData['name'])
					->setLink($postData['link'])
					->setParentId($postData['parent_id'])
					->setSort($postData['sort'])
					->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setCustomercenterData(false);

				$this->_redirect('*/*/');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setCustomercenterData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		$this->_redirect('*/*/');
	}
	public function deleteAction()
	{
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$customercenterModel = Mage::getModel('silverframework/cmslinks');
				$customercenterModel->setId($this->getRequest()->getParam('id'))
					->delete();
					
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
}