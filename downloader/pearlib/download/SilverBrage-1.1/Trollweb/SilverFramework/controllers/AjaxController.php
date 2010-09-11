<?php

class Trollweb_SilverFramework_AjaxController extends Mage_Core_Controller_Front_Action
{

    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = Mage::getModel('catalog/category')
                ->setData($category->getData())
                ->getUrl();
        }
		return $url;
	}
      

	public function getCategoryChildrenAction()
	{
		$category_id = $this->getRequest()->getParam('category_id');
		$model = Mage::getModel('catalog/category');
		$model->load($category_id);
		
		$_categories = $model->getChildrenCategories();
		$categories = array();
	
		foreach($_categories as $_category)	{
			$categories[] = array(
				'id' => $_category->getId(),
				'name' => $_category->getName(),
				'url' => $this->getCategoryUrl($_category),
				'children' => $_category->getChildrenCategories()->count()
			);
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($categories));
		return;
	}


	public function indexAction()
	{
		die('yeah!'); 
	}
}