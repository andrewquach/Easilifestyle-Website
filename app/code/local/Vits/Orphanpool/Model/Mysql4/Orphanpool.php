<?php

class Vits_Orphanpool_Model_Mysql4_Orphanpool extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the orphanpool_id refers to the key field in your database table.
        $this->_init('orphanpool/orphanpool', 'orphanpool_id');
    }
}