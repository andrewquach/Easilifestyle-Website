<?php

class Vits_Transactions_Block_Adminhtml_Transactions_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('transactions_form', array('legend'=>Mage::helper('transactions')->__('Transaction information')));
     
      $customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	    
	    array_unshift($customer_ids, array('value'=>'', 'label'=>Mage::helper('transactions')->__('--Select--')));
		
		
		   
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('transactions')->__('Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_id',
      	  'values' 	=> $customer_ids
      ));
      
      $fieldset->addField('remark', 'text', array(
          'label'     => Mage::helper('transactions')->__('Transaction Remark'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'remark',
      ));
      
      $fieldset->addField('transactions_time', 'date', array(
          'label'     => Mage::helper('transactions')->__('Transaction Time'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'transactions_time',
          'format' => 'yyyy-MM-dd',
      	  'image'  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif',
      ));
      
      $fieldset->addField('amount', 'text', array(
          'label'     => Mage::helper('transactions')->__('Transaction Amount'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'amount',
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('transactions')->__('Transaction Status'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'status',
      	  'values'    => array(
      			array(
                  'value'     => '',
                  'label'     => Mage::helper('transactions')->__('--Select--'),
              ),
              array(
                  'value'     => 'enable',
                  'label'     => Mage::helper('transactions')->__('Enable'),
              ),

              array(
                  'value'     => 'disable',
                  'label'     => Mage::helper('transactions')->__('Disable'),
              ),
	     ),
      ));
      
       $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('transactions')->__('Transaction Type'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'type',
           'values'    => array(
       			array(
                  'value'     => '',
                  'label'     => Mage::helper('transactions')->__('--Select--'),
              ),
              array(
                  'value'     => 'commission',
                  'label'     => Mage::helper('transactions')->__('Commission'),
              ),

              array(
                  'value'     => 'sale',
                  'label'     => Mage::helper('transactions')->__('Sale Product'),
              ),
              array(
                  'value'     => 'payment',
                  'label'     => Mage::helper('transactions')->__('Admin Payment'),
              ),
	     ),
      ));

		
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('transactions')->__('Description'),
          'title'     => Mage::helper('transactions')->__('Description'),
          //'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->gettransactionsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->gettransactionsData());
          Mage::getSingleton('adminhtml/session')->settransactionsData(null);
      } elseif ( Mage::registry('transactions_data') ) {
          $form->setValues(Mage::registry('transactions_data')->getData());
      }
      return parent::_prepareForm();
  }
}