<?php

class Vits_Redemption_Block_Adminhtml_Redemption_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('redemption_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('redemption')->__('Redemption Order Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('redemption')->__('Redemption Order Information'),
          'title'     => Mage::helper('redemption')->__('Redemption Order Information'),
          'content'   => $this->getLayout()->createBlock('redemption/adminhtml_redemption_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}