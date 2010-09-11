<?php

class Trollweb_SilverFramework_Model_Observer extends Mage_Core_Model_Abstract	{
	
	function checkValidity($observer)
	{
		$helper = Mage::helper('silverframework/silver');
		$serial = $helper->settings['about']['serial'];
		$domain = $_SERVER['SERVER_NAME'];
		$key = 'silverframework';
		
		if(sha1($key.$domain) != $serial)	{
			$event = $observer->getEvent();
			$layout = Mage::app()->getLayout();
			$root = $layout->getBlock('root');
			$left = $layout->getBlock('top.menu');
			$block = $layout->createBlock('core/template')->setTemplate('silverframework/check.phtml');
			$left->insert($block);
		}
		
		

	}
	
	function updateBodyClassEvent($observer)
	{
		$event = $observer->getEvent();
		$layout = Mage::app()->getLayout();
		$helper = Mage::helper('silverframework/silver');
		$root = $layout->getBlock('root');
		$font = $helper->settings['settings']['font_family'];
		
		if($font != '')	{
			$className = 'silverframework-font-' . $font;
			
			if($root && is_object($root) && method_exists($root, 'addBodyClass'))	{
				$root->addBodyClass($className);
			}
		}
	}
}
