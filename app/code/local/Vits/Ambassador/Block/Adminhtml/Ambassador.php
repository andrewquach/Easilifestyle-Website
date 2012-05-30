<?php
class Vits_Ambassador_Block_Adminhtml_Ambassador extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_ambassador';
    $this->_blockGroup = 'ambassador';
    $this->_headerText = Mage::helper('ambassador')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('ambassador')->__('Add Item');
    parent::__construct();
  }
}