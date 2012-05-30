<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
 public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'companypool';
        $this->_controller = 'adminhtml_companypool';
        
        $this->_updateButton('save', 'label', Mage::helper('companypool')->__('Save Company Pool'));
        $this->_updateButton('delete', 'label', Mage::helper('companypool')->__('Delete Company Pool'));
		
       /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('companypool_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'companypool_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'companypool_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('companypool_data') && Mage::registry('companypool_data')->getId() ) {
            return Mage::helper('companypool')->__("Edit Company Pool[%s]",$this->htmlEscape(Mage::registry('companypool_data')->getCompanyTime()));
        } else {
            return Mage::helper('companypool')->__('Add Company Pool');
        }
    }
}