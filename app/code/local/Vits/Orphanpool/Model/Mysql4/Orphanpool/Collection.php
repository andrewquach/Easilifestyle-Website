<?php

class Vits_Orphanpool_Model_Mysql4_Orphanpool_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('orphanpool/orphanpool');
    }
}