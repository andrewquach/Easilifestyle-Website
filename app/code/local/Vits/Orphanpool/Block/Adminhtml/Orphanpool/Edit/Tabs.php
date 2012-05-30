<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

public function __construct()
  {
      parent::__construct();
      $this->setId('orphanpool_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('orphanpool')->__('Walk-in Pool Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('orphanpool')->__('Walk-in-Pool Information'),
          'title'     => Mage::helper('orphanpool')->__('Walk-in Pool Information'),
          'content'   => $this->getLayout()->createBlock('orphanpool/adminhtml_orphanpool_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}