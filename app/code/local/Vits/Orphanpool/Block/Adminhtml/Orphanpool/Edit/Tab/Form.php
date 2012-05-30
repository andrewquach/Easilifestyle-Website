<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('orphanpool_form', array('legend'=>Mage::helper('orphanpool')->__('Walk-in Pool Information')));
     
      $fieldset->addField('orphan_real_amount', 'text', array(
          'label'     => Mage::helper('orphanpool')->__('Walk-in-Pool Real Amount'),
          'class'     => 'required-entry',
      	  'note'   => 'BV', 
          'required'  => true,
          'name'      => 'orphan_real_amount',
      ));

      $fieldset->addField('orphan_edit_amount', 'text', array(
          'label'     => Mage::helper('orphanpool')->__('Walk-in-Pool Edit Amount'),
          'class'     => 'required-entry',
      	  'note'   => 'BV',
          'required'  => true,
          'name'      => 'orphan_edit_amount',
      ));
		
      $fieldset->addField('notes', 'editor', array(
          'name'      => 'notes',
          'label'     => Mage::helper('orphanpool')->__('Notes'),
          'title'     => Mage::helper('orphanpool')->__('Notes'),
          //'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getOrphanPoolData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getOrphanPoolData());
          Mage::getSingleton('adminhtml/session')->setOrphanPoolData(null);
      } elseif ( Mage::registry('orphanpool_data') ) {
          $form->setValues(Mage::registry('orphanpool_data')->getData());
      }
      return parent::_prepareForm();
  }
}