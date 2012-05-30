<?php
class Vits_Companypool_Block_Adminhtml_Companypool extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
  {
    $this->_controller = 'adminhtml_companypool';
    $this->_blockGroup = 'companypool';
    $this->_headerText = Mage::helper('companypool')->__('Company Pool Manager');
    $this->_addButtonLabel = Mage::helper('companypool')->__('Add Company Pool');
    parent::__construct();
  }
  
  public function _prepareLayout()
  {
  		$this->_removeButton('add');
        return parent::_prepareLayout();
  }
}