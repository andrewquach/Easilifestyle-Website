<?php

class Vits_Redemption_Model_Mysql4_Redemption extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the redemption_id refers to the key field in your database table.
        $this->_init('redemption/redemption', 'redemption_id');
    }
}