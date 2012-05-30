<?php

class Vits_Orphanpool_Model_Orphanpool extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('orphanpool/orphanpool');
    }
}