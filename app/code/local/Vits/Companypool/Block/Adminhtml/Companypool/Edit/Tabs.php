<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

public function __construct()
  {
      parent::__construct();
      $this->setId('companypool_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('companypool')->__('Company Pool Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('companypool')->__('Company Pool Information'),
          'title'     => Mage::helper('companypool')->__('Company Pool Information'),
          'content'   => $this->getLayout()->createBlock('companypool/adminhtml_companypool_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}