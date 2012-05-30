<?php
class Vits_Redemption_Block_Adminhtml_Redemption extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_redemption';
    $this->_blockGroup = 'redemption';
    $this->_headerText = Mage::helper('redemption')->__('Redemption Product Order Manager');
    $this->_addButtonLabel = Mage::helper('redemption')->__('Add New');
    parent::__construct();
  }
	public function _prepareLayout()
  {
  		$this->_removeButton('add');
        return parent::_prepareLayout();
  }
}