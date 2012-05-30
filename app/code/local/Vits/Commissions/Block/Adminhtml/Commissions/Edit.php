<?php

class Vits_Commissions_Block_Adminhtml_Commissions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'commissions';
        $this->_controller = 'adminhtml_commissions';
        
        $this->_updateButton('save', 'label', Mage::helper('commissions')->__('Save Commission'));
        $this->_updateButton('delete', 'label', Mage::helper('commissions')->__('Delete Commission'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('commissions_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'commissions_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'commissions_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('commissions_data') && Mage::registry('commissions_data')->getId() ) {
            return Mage::helper('commissions')->__("Edit Commission '%s'", $this->htmlEscape(Mage::registry('commissions_data')->getTitle()));
        } else {
            return Mage::helper('commissions')->__('Add Commission');
        }
    }
}