<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('companypool_form', array('legend'=>Mage::helper('companypool')->__('Company Pool information')));
     
      $fieldset->addField('company_real_amount', 'text', array(
          'label'     => Mage::helper('companypool')->__('Company Real Amount'),
          'class'     => 'required-entry',
          'note'   => 'BV',
          'required'  => true,
          'name'      => 'company_real_amount',
      ));

      $fieldset->addField('company_edit_amount', 'text', array(
          'label'     => Mage::helper('companypool')->__('Company Edit Amount'),
          'class'     => 'required-entry',
      	  'note'   => 'BV',
          'required'  => true,
          'name'      => 'company_edit_amount',
	  ));
		
      $fieldset->addField('notes', 'textarea', array(
          'name'      => 'notes',
          'label'     => Mage::helper('companypool')->__('Notes'),
          'title'     => Mage::helper('companypool')->__('Notes'),
          //'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCompanyPoolData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCompanyPoolData());
          Mage::getSingleton('adminhtml/session')->setCompanyPoolData(null);
      } elseif ( Mage::registry('companypool_data') ) {
          $form->setValues(Mage::registry('companypool_data')->getData());
      }
      return parent::_prepareForm();
  }
}