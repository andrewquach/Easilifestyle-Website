<?php
class Vits_Affiliatecommission_Block_Adminhtml_Affiliatecommission extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
  {
    $this->_controller = 'adminhtml_affiliatecommission';
    $this->_blockGroup = 'affiliatecommission';
    $this->_headerText = Mage::helper('affiliatecommission')->__('Affiliate Commission Manager');
    $this->_addButtonLabel = Mage::helper('affiliatecommission')->__('Add Affiliate Commission');
    parent::__construct();
  }
  
  public function _prepareLayout()
  {
  		$this->_removeButton('add');
        return parent::_prepareLayout();
  }
}