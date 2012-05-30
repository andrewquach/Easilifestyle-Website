<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'orphanpool';
        $this->_controller = 'adminhtml_orphanpool';
        
        $this->_updateButton('save', 'label', Mage::helper('orphanpool')->__('Save Walk-in-Pool'));
        $this->_updateButton('delete', 'label', Mage::helper('orphanpool')->__('Delete Walk-in-Pool'));
		
       /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);*/

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('orphanpool_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'orphanpool_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'orphanpool_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('orphanpool_data') && Mage::registry('orphanpool_data')->getId() ) {
            return Mage::helper('orphanpool')->__("Edit Walk-in-Pool [%s]",$this->htmlEscape(Mage::registry('orphanpool_data')->getOrphanTime()));
        } else {
            return Mage::helper('orphanpool')->__('Add Walk-in-Pool');
        }
    }
}