<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ambassador')};
CREATE TABLE {$this->getTable('ambassador')} (
  `ambassador_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `ambassador_type` int(20) NOT NULL default '0',
  `ambassador_name` varchar(100) NOT NULL default '',
  `ambassador_text` varchar(100) NOT NULL default '',
  `ambassador_redirect_custom_url` varchar(255) NOT NULL default '',
  `ambassador_height` smallint(6) NOT NULL default '0',
  `ambassador_width` smallint(6) NOT NULL default '0',
  `ambassador_file_name` varchar(50) NOT NULL default '',
  `ambassador_file_size` varchar(50) NOT NULL default '',
  `ambassador_file_type` varchar(100) NOT NULL default '',
  `ambassador_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`ambassador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
