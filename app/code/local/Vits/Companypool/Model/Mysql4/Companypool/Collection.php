<?php

class Vits_Companypool_Model_Mysql4_Companypool_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('companypool/companypool');
    }
}