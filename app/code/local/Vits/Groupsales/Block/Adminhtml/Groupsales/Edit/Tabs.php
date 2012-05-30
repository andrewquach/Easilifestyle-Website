<?php

class Vits_Groupsales_Block_Adminhtml_Groupsales_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('groupsales_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('groupsales')->__('Groupsales Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('groupsales')->__('Item Information'),
          'title'     => Mage::helper('groupsales')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('groupsales/adminhtml_groupsales_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}