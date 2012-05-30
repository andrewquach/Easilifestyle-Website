<?php

class Vits_Affiliatecommission_Model_Mysql4_Affiliatecommission extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the affiliatecommission_id refers to the key field in your database table.
        $this->_init('affiliatecommission/affiliatecommission', 'affiliatecommission_id');
    }
}