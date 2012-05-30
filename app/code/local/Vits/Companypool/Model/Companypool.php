<?php

class Vits_Companypool_Model_Companypool extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('companypool/companypool');
    }
}