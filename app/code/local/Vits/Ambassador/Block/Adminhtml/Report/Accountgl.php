<?php
class Vits_Ambassador_Block_Adminhtml_Report_Accountgl extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize container block settings
     *
     */
    public function __construct()
    {
        $this->_controller = 'report_accountgl';
        parent::__construct();
        $this->_removeButton('add');
    }
}
?>