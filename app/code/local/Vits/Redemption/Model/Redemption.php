<?php

class Vits_Redemption_Model_Redemption extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('redemption/redemption');
    }
}