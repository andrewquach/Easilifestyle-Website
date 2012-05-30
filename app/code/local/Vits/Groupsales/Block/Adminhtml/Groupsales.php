<?php
class Vits_Groupsales_Block_Adminhtml_Groupsales extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_groupsales';
    $this->_blockGroup = 'groupsales';
    $this->_headerText = Mage::helper('groupsales')->__('Groupsales Manager');
    $this->_addButtonLabel = Mage::helper('groupsales')->__('Add Groupsales');
    parent::__construct();
  }
  
  public function _prepareLayout()
  {
  		$this->_removeButton('add');
        return parent::_prepareLayout();
  }
}