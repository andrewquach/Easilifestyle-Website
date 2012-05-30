<?php

class Vits_Commissions_Block_Adminhtml_Commissions_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('commissions_form', array('legend'=>Mage::helper('commissions')->__('Commissions information')));
     
		$customer_data=Mage::getResourceModel('customer/customer_collection')
        ->addAttributeToSelect('firstname')
        ->addAttributeToSelect('lastname');
      
	    $customer_ids = array();
	    foreach ($customer_data as $data):
	    $customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
	    endforeach;
	    
	    array_unshift($customer_ids, array('value'=>'', 'label'=>Mage::helper('commissions')->__('Select')));
		
		
		   
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('commissions')->__('Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_id',
      	  'values' 	=> $customer_ids
      ));
      
      $fieldset->addField('remark', 'text', array(
          'label'     => Mage::helper('commissions')->__('Remark'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'remark',
      ));
      
      $fieldset->addField('commission_time', 'date', array(
          'label'     => Mage::helper('commissions')->__('Time'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'commission_time',
          'format' => 'yyyy-MM-dd',
      	  'image'  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif',
      ));
      
      $fieldset->addField('amount', 'text', array(
          'label'     => Mage::helper('commissions')->__('Amount'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'amount',
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('commissions')->__('Status'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'status',
      	  'values'    => array(
              array(
                  'value'     => 'available',
                  'label'     => Mage::helper('commissions')->__('Available'),
              ),

              array(
                  'value'     => 'Not available',
                  'label'     => Mage::helper('commissions')->__('Not Available'),
              ),
	     ),
      ));
      
       $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('commissions')->__('Type'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'type',
           'values'    => array(
              array(
                  'value'     => 'type1',
                  'label'     => Mage::helper('commissions')->__('Type1'),
              ),

              array(
                  'value'     => 'type2',
                  'label'     => Mage::helper('commissions')->__('type2'),
              ),
	     ),
      ));

		
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('commissions')->__('Description'),
          'title'     => Mage::helper('commissions')->__('Description'),
          //'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCommissionsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCommissionsData());
          Mage::getSingleton('adminhtml/session')->setCommissionsData(null);
      } elseif ( Mage::registry('commissions_data') ) {
          $form->setValues(Mage::registry('commissions_data')->getData());
      }
      return parent::_prepareForm();
  }
}