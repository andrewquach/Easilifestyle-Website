<?php

class Vits_Groupsales_Block_Adminhtml_Groupsales_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('groupsales_form', array('legend'=>Mage::helper('groupsales')->__('Groupsales information')));
     
      
        $customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	    
	    array_unshift($customer_ids, array('value'=>'', 'label'=>Mage::helper('groupsales')->__('Select')));
	    
	    
	    
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('groupsales')->__('Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_id',
      	  'values' 	=> $customer_ids
      ));
      
      $fieldset->addField('before_groupsales', 'text', array(
          'label'     => Mage::helper('groupsales')->__('Before Groupsales'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'before_groupsales',
      ));
      
      $fieldset->addField('after_groupsales', 'text', array(
          'label'     => Mage::helper('groupsales')->__('After Groupsales'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'after_groupsales',
      ));

		
     
      $fieldset->addField('notes', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('groupsales')->__('Notes'),
          'title'     => Mage::helper('groupsales')->__('Notes'),
          //'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getGroupsalesData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getGroupsalesData());
          Mage::getSingleton('adminhtml/session')->setGroupsalesData(null);
      } elseif ( Mage::registry('groupsales_data') ) {
          $form->setValues(Mage::registry('groupsales_data')->getData());
      }
      return parent::_prepareForm();
  }
}