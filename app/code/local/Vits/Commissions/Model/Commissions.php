<?php

class Vits_Commissions_Model_Commissions extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('commissions/commissions');
    }
}