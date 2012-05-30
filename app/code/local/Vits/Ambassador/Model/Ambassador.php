<?php

class Vits_Ambassador_Model_Ambassador extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ambassador/ambassador');
    }
}
?>