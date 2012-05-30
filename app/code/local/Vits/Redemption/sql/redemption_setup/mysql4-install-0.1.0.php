<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('redemption')};
CREATE TABLE {$this->getTable('redemption')} (
  `redemption_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `redemption_type` int(20) NOT NULL default '0',
  `redemption_name` varchar(100) NOT NULL default '',
  `redemption_text` varchar(100) NOT NULL default '',
  `redemption_redirect_custom_url` varchar(255) NOT NULL default '',
  `redemption_height` smallint(6) NOT NULL default '0',
  `redemption_width` smallint(6) NOT NULL default '0',
  `redemption_file_name` varchar(50) NOT NULL default '',
  `redemption_file_size` varchar(50) NOT NULL default '',
  `redemption_file_type` varchar(100) NOT NULL default '',
  `redemption_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`redemption_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
