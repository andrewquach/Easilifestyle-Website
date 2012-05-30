<?php
class Vits_Ambassador_Block_Adminhtml_Report_Transactions extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize container block settings
     *
     */
    public function __construct()
    {
        $this->_controller = 'report_transactions';
        $this->_headerText = Mage::helper('reports')->__('Commission Detail');
        parent::__construct();
        $this->_removeButton('add');
    }
}
?>