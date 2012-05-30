<?php

class Vits_Redemption_Block_Adminhtml_Redemption_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('redemption_form', array('legend'=>Mage::helper('redemption')->__('Redemption Order information')));
      $customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	    
	    array_unshift($customer_ids, array('value'=>'', 'label'=>Mage::helper('transactions')->__('--Select--')));
     
	    $product_data=Mage::getModel('catalog/product')->getCollection()
        ->addAttributeToSelect('name');
      
	    $product_ids = array();
	    foreach ($product_data as $data):
	    $product_ids[$data->getId()] = $data->getName();
	    endforeach;
	    array_unshift($product_ids, array('value'=>'', 'label'=>Mage::helper('transactions')->__('--Select--')));
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('redemption')->__('Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_id',
      	  'values' 	=> $customer_ids
      ));
      $fieldset->addField('product_id', 'select', array(
          'label'     => Mage::helper('redemption')->__('Product'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'product_id',
      	  'values' 	=> $product_ids
      ));
      $fieldset->addField('qty', 'text', array(
          'label'     => Mage::helper('redemption')->__('Qty'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'qty',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('redemption')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 'pending',
                  'label'     => Mage::helper('redemption')->__('Pending'),
              ),

              array(
                  'value'     => 'processing',
                  'label'     => Mage::helper('redemption')->__('Processing'),
              ),
               array(
                  'value'     => 'complete',
                  'label'     => Mage::helper('redemption')->__('Completed'),
              ),
               array(
                  'value'     => 'cancel',
                  'label'     => Mage::helper('redemption')->__('Canceled'),
              ),
          ),
      ));
     
     
      if ( Mage::getSingleton('adminhtml/session')->getRedemptionData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRedemptionData());
          Mage::getSingleton('adminhtml/session')->setRedemptionData(null);
      } elseif ( Mage::registry('redemption_data') ) {
          $form->setValues(Mage::registry('redemption_data')->getData());
      }
      return parent::_prepareForm();
  }
}