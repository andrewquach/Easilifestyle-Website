<?php
class Vits_Ambassador_Model_Mysql4_Groupglused_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ambassador/groupglused');
    }
}