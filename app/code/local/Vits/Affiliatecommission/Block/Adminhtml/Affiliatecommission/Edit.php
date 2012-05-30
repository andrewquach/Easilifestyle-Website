<?php

class Vits_Affiliatecommission_Block_Adminhtml_Affiliatecommission_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
 public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'affiliatecommission';
        $this->_controller = 'adminhtml_affiliatecommission';
        
        $this->_updateButton('save', 'label', Mage::helper('affiliatecommission')->__('Save Company Pool'));
        $this->_updateButton('delete', 'label', Mage::helper('affiliatecommission')->__('Delete Company Pool'));
		
       /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('affiliatecommission_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'affiliatecommission_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'affiliatecommission_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('affiliatecommission_data') && Mage::registry('affiliatecommission_data')->getId() ) {
            return Mage::helper('affiliatecommission')->__("Edit Company Pool[%s]",$this->htmlEscape(Mage::registry('affiliatecommission_data')->getCompanyTime()));
        } else {
            return Mage::helper('affiliatecommission')->__('Add Company Pool');
        }
    }
}