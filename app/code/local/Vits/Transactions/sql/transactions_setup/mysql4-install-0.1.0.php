<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('transactions')};
CREATE TABLE {$this->getTable('transactions')} (
  `transactions_id` int(11) unsigned NOT NULL auto_increment,
  `status` smallint(6) NOT NULL default '0',
  `transactions_type` int(20) NOT NULL default '0',
  `transactions_name` varchar(100) NOT NULL default '',
  `transactions_text` varchar(100) NOT NULL default '',
  `transactions_redirect_custom_url` varchar(255) NOT NULL default '',
  `transactions_height` smallint(6) NOT NULL default '0',
  `transactions_width` smallint(6) NOT NULL default '0',
  `transactions_file_name` varchar(50) NOT NULL default '',
  `transactions_file_size` varchar(50) NOT NULL default '',
  `transactions_file_type` varchar(100) NOT NULL default '',
  `transactions_notes` text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`transactions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 


  
