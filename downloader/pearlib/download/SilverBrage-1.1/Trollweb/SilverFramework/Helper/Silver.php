<?php

class Trollweb_SilverFramework_Helper_Silver extends Mage_Core_Helper_Abstract
{
	public function __construct()
	{
		$this->settings = $this->loadConfig();
	}
	
	public function getTabs()
	{
		$customerCenter = Mage::getModel('silverframework/tabs')->getCollection();
		$customerCenter->setOrder('sort', 'asc');
		return $customerCenter;	
	}	
	
	public function getCmslinks()
	{
		$customerCenter = Mage::getModel('silverframework/cmslinks')->getCollection();
		$customerCenter->setOrder('sort', 'asc');
		$customerCenter->addFieldToFilter('parent_id', 0);
		return $customerCenter;	
	}
	
	public function getCmslinksChildren($parent_id)
	{
		$children = Mage::getModel('silverframework/cmslinks')
			->getCollection()
			->setOrder('sort', 'asc')
			->addFieldToFilter('parent_id', $parent_id);
		 
		
		return $children;
	}
	
	
	public function getCustomercenter()
	{
		$customerCenter = Mage::getModel('silverframework/customercenter')->getCollection();
		$customerCenter->setOrder('sort', 'asc');
		$customerCenter->addFieldToFilter('parent_id', 0);
		return $customerCenter;
	}
	
	public function getCustomercenterChildren($parent_id)
	{
		$children = Mage::getModel('silverframework/customercenter')
			->getCollection()
			->setOrder('sort', 'asc')
			->addFieldToFilter('parent_id', $parent_id);
			
		if($children->count())	{
			return $children;
		}
		
		return false;
	}

	public function loadConfig()
	{	
		$settings = array();
		$items = array(
			'about' => array(
				'general/serial',
			
			),
			'settings' => array(
				'header/enable_currency_chooser',
				'header/enable_storeview_chooser',
				'product/zoom_type',
				'product/zoom_pagination',
				'product/zoom_captions',
				'product/highslide_border',
				'product/share_product_page',
				'product/sharethis_publisher_id',
				'product/addthis_username',
				'product/tabs_display',
				'navigation/category_levels',
				'navigation/toggle_menu',
				'navigation/show_non_catalog',
				'navigation/toggle_link_takeover',
				'navigation/use_ajax',
				'footer/footer_text',
				'theme_settings/color',
				'product_list/show_toolbar',
				'general/font_family',
				'callouts/left_callout_enable',
				'callouts/right_callout_enable',
				'callouts/left_callout_html',
				'callouts/right_callout_html',
			)
		);
		
		foreach($items as $key => $value)	{
			$settings[$key] = array();
			foreach($value as $setting_item)	{
				$path = 'silverframework_' . $key . '/' . $setting_item;
				$temp = explode('/', $setting_item);

				if(count($temp) == 2) {
					$name = $temp[1];
				}
				else {
					$name = $temp[0];
				}


				$settings[$key][$name] = Mage::getStoreConfig($path);
			}
		}

		return $settings;
	}


}
