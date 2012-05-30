<?php

class Vits_Commissions_Model_Mysql4_Commissions extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the commissions_id refers to the key field in your database table.
        $this->_init('commissions/commissions', 'commissions_id');
    }
}