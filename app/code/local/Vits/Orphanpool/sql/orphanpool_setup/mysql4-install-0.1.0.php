<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('orphanpool')};
CREATE TABLE {$this->getTable('orphanpool')} (
  `orphanpool_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `orphanpool_type` int(20) NOT NULL default '0',
  `orphanpool_name` varchar(100) NOT NULL default '',
  `orphanpool_text` varchar(100) NOT NULL default '',
  `orphanpool_redirect_custom_url` varchar(255) NOT NULL default '',
  `orphanpool_height` smallint(6) NOT NULL default '0',
  `orphanpool_width` smallint(6) NOT NULL default '0',
  `orphanpool_file_name` varchar(50) NOT NULL default '',
  `orphanpool_file_size` varchar(50) NOT NULL default '',
  `orphanpool_file_type` varchar(100) NOT NULL default '',
  `orphanpool_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`orphanpool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
