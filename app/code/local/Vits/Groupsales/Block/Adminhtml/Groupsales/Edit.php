<?php

class Vits_Groupsales_Block_Adminhtml_Groupsales_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'groupsales';
        $this->_controller = 'adminhtml_groupsales';
        
        $this->_updateButton('save', 'label', Mage::helper('groupsales')->__('Save Groupsales'));
        $this->_updateButton('delete', 'label', Mage::helper('groupsales')->__('Delete Groupsales'));
		
      /*  $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('groupsales_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'groupsales_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'groupsales_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('groupsales_data') && Mage::registry('groupsales_data')->getId() ) {
            return Mage::helper('groupsales')->__("Edit Group Sale");
        } else {
            return Mage::helper('groupsales')->__('Add Group Sale');
        }
    }
}