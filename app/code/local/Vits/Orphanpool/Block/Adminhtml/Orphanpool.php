<?php
class Vits_Orphanpool_Block_Adminhtml_Orphanpool extends Mage_Adminhtml_Block_Widget_Grid_Container
{
public function __construct()
  {
    $this->_controller = 'adminhtml_orphanpool';
    $this->_blockGroup = 'orphanpool';
    $this->_headerText = Mage::helper('orphanpool')->__('Walk-in-Pool Manager');
    $this->_addButtonLabel = Mage::helper('orphanpool')->__('Add Walk-in-Pool');	
    parent::__construct();
  }
  
  public function _prepareLayout()
  {
  		$this->_removeButton('add');
        return parent::_prepareLayout();
		
  }
}