<?php

class Vits_Groupsales_Model_Mysql4_Groupsales extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the groupsales_id refers to the key field in your database table.
        $this->_init('groupsales/groupsales', 'groupsales_id');
    }
}