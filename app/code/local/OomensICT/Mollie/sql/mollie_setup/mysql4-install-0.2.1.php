<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

$installer = $this;
/* @var $installer OomensICT_Mollie_Model_Mysql4_Setup */

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$this->getTable('mollie_api_debug')}`;
CREATE TABLE `{$this->getTable('mollie_api_debug')}` (
  `debug_id` int(10) unsigned NOT NULL auto_increment,
  `debug_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `request_body` text,
  `response_body` text,
  PRIMARY KEY  (`debug_id`),
  KEY `debug_at` (`debug_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();

$installer->addAttribute('quote_payment', 'mollie_bank_id', array());
$installer->addAttribute('quote_payment', 'mollie_bank_list', array('type' => 'text'));
$installer->addAttribute('order_payment', 'mollie_bank_id', array());
$installer->addAttribute('order_payment', 'mollie_bank_title', array());
$installer->addAttribute('order', 'mollie_transaction_id', array());
$installer->addAttribute('order', 'mollie_paid_status', array('type' => 'int'));

