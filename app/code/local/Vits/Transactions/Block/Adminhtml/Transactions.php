<?php
class Vits_Transactions_Block_Adminhtml_Transactions extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_transactions';
    $this->_blockGroup = 'transactions';
    $this->_headerText = Mage::helper('transactions')->__('Transaction Manager');
    $this->_addButtonLabel = Mage::helper('transactions')->__('Add New Transaction');
    parent::__construct();
  }
  
  public function _prepareLayout()
  {
  		// $this->_removeButton('add');
        return parent::_prepareLayout();
  }
}