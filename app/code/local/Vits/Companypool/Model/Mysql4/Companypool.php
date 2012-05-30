<?php

class Vits_Companypool_Model_Mysql4_Companypool extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the companypool_id refers to the key field in your database table.
        $this->_init('companypool/companypool', 'companypool_id');
    }
}