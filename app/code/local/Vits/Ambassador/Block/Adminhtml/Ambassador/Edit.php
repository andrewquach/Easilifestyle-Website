<?php

class Vits_Ambassador_Block_Adminhtml_Ambassador_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'ambassador';
        $this->_controller = 'adminhtml_ambassador';
        
        $this->_updateButton('save', 'label', Mage::helper('ambassador')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('ambassador')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('ambassador_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'ambassador_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'ambassador_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('ambassador_data') && Mage::registry('ambassador_data')->getId() ) {
            return Mage::helper('ambassador')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('ambassador_data')->getTitle()));
        } else {
            return Mage::helper('ambassador')->__('Add Item');
        }
    }
}