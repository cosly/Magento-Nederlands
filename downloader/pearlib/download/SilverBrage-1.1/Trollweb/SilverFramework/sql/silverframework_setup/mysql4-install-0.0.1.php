<?php

$installer = $this;
$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('silverframework_customercenter')};

CREATE TABLE {$this->getTable('silverframework_customercenter')} (
  `customercenter_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `link` text NOT NULL default '',
  `sort` int(4) default '0',
  `parent_id` int(11) default '0',
  PRIMARY KEY (`customercenter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	 
-- DROP TABLE IF EXISTS {$this->getTable('silverframework_cmslinks')};

CREATE TABLE {$this->getTable('silverframework_cmslinks')} (
  `cmslink_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `link` text NOT NULL default '',
  `sort` int(4) default '0',
  `parent_id` int(11) default '0',
  PRIMARY KEY (`cmslink_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	 
-- DROP TABLE IF EXISTS {$this->getTable('silverframework_tabs')};

CREATE TABLE {$this->getTable('silverframework_tabs')} (
  `tab_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `type` varchar(30) NOT NULL default '',
  `content` text NOT NULL default '',	
  `sort` int(4) default '0',
  PRIMARY KEY (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
INSERT INTO {$this->getTable('silverframework_tabs')} (name,type,content,sort) VALUES ('Product Description', 'child', 'description', 1);

INSERT INTO {$this->getTable('silverframework_tabs')} (name,type,content,sort) VALUES ('Product Tags', 'child', 'product_additional_data', 2);

INSERT INTO {$this->getTable('silverframework_tabs')} (name,type,content,sort) VALUES ('Related Products', 'child', 'upsell_products', 3);
	 
");