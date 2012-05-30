<?php

class Vits_Transactions_Block_Adminhtml_Transactions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('transactions_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('transactions')->__('Transaction Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('transactions')->__('Transaction Information'),
          'title'     => Mage::helper('transactions')->__('Transaction Information'),
          'content'   => $this->getLayout()->createBlock('transactions/adminhtml_transactions_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}