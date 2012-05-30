<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('groupsales')};
CREATE TABLE {$this->getTable('groupsales')} (
  `groupsales_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `groupsales_type` int(20) NOT NULL default '0',
  `groupsales_name` varchar(100) NOT NULL default '',
  `groupsales_text` varchar(100) NOT NULL default '',
  `groupsales_redirect_custom_url` varchar(255) NOT NULL default '',
  `groupsales_height` smallint(6) NOT NULL default '0',
  `groupsales_width` smallint(6) NOT NULL default '0',
  `groupsales_file_name` varchar(50) NOT NULL default '',
  `groupsales_file_size` varchar(50) NOT NULL default '',
  `groupsales_file_type` varchar(100) NOT NULL default '',
  `groupsales_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`groupsales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
