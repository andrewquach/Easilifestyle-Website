<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('companypool')};
CREATE TABLE {$this->getTable('companypool')} (
  `companypool_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `companypool_type` int(20) NOT NULL default '0',
  `companypool_name` varchar(100) NOT NULL default '',
  `companypool_text` varchar(100) NOT NULL default '',
  `companypool_redirect_custom_url` varchar(255) NOT NULL default '',
  `companypool_height` smallint(6) NOT NULL default '0',
  `companypool_width` smallint(6) NOT NULL default '0',
  `companypool_file_name` varchar(50) NOT NULL default '',
  `companypool_file_size` varchar(50) NOT NULL default '',
  `companypool_file_type` varchar(100) NOT NULL default '',
  `companypool_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`companypool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
