<?php

class Vits_Groupsales_Model_Groupsales extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('groupsales/groupsales');
    }
}