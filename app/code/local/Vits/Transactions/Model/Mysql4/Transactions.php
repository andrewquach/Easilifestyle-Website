<?php

class Vits_Transactions_Model_Mysql4_Transactions extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the transactions_id refers to the key field in your database table.
        $this->_init('transactions/transactions', 'transactions_id');
    }
}