<?php

class Vits_Ambassador_Model_Mysql4_OrphanPool extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the ambassador_id refers to the key field in your database table.
        $this->_init('ambassador/orphanPool', 'id');
    }
}
?>