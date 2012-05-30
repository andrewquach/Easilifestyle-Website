<?php
class Vits_Commissions_Block_Adminhtml_Commissions extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_commissions';
    $this->_blockGroup = 'commissions';
    $this->_headerText = Mage::helper('commissions')->__('Commission Manager');
    $this->_addButtonLabel = Mage::helper('commissions')->__('Add Commission');
    parent::__construct();
  }
}