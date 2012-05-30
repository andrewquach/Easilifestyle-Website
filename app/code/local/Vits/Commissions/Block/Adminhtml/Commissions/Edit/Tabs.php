<?php

class Vits_Commissions_Block_Adminhtml_Commissions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('commissions_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('commissions')->__('Commission Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('commissions')->__('Commissions Information'),
          'title'     => Mage::helper('commissions')->__('Commissions Information'),
          'content'   => $this->getLayout()->createBlock('commissions/adminhtml_commissions_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}