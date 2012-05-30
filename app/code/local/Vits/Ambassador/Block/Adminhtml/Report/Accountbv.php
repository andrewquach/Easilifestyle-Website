<?php
class Vits_Ambassador_Block_Adminhtml_Report_Accountbv extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize container block settings
     *
     */
    public function __construct()
    {
        $this->_controller = 'report_accountbv';
        $this->_headerText = Mage::helper('reports')->__('Accumulate BV');
        parent::__construct();
        $this->_removeButton('add');
    }
}
?>