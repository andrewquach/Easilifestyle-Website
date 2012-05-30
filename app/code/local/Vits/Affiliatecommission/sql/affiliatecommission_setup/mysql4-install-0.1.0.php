<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('affiliatecommission')};
CREATE TABLE {$this->getTable('affiliatecommission')} (
  `affiliatecommission_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `affiliatecommission_type` int(20) NOT NULL default '0',
  `affiliatecommission_name` varchar(100) NOT NULL default '',
  `affiliatecommission_text` varchar(100) NOT NULL default '',
  `affiliatecommission_redirect_custom_url` varchar(255) NOT NULL default '',
  `affiliatecommission_height` smallint(6) NOT NULL default '0',
  `affiliatecommission_width` smallint(6) NOT NULL default '0',
  `affiliatecommission_file_name` varchar(50) NOT NULL default '',
  `affiliatecommission_file_size` varchar(50) NOT NULL default '',
  `affiliatecommission_file_type` varchar(100) NOT NULL default '',
  `affiliatecommission_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`affiliatecommission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
