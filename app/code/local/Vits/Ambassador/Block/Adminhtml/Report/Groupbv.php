<?php
class Vits_Ambassador_Block_Adminhtml_Report_Groupbv extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize container block settings
     *
     */
    public function __construct()
    {
        $this->_controller = 'report_groupbv';
        $this->_headerText = Mage::helper('reports')->__('Products Ordered');
        parent::__construct();
        $this->_removeButton('add');
    }
}
?>