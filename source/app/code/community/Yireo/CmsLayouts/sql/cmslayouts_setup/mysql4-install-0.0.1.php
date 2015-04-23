<?php
/**
 * Yireo CmsLayouts for Magento
 *
 * @package     Yireo_CmsLayouts_
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('cmslayouts_mockup')} (
  `mockup_id` int(11) NOT NULL auto_increment,
  `label` varchar(255) NOT NULL DEFAULT 0,
  `identifier` varchar(255) NOT NULL DEFAULT 0,
  `layout` varchar(255) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL auto_increment,
  PRIMARY KEY  (`mockup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('cmslayouts_element')} (
  `element_id` int(11) NOT NULL auto_increment,
  `mockup_id` int(11) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL DEFAULT 0,
  `value` text NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL auto_increment,
  PRIMARY KEY  (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
