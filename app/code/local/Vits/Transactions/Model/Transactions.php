<?php

class Vits_Transactions_Model_Transactions extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('transactions/transactions');
    }
}