<?php
class Vits_Groupsales_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/groupsales?id=15 
    	 *  or
    	 * http://site.com/groupsales/id/15 	
    	 */
    	/* 
		$groupsales_id = $this->getRequest()->getParam('id');

  		if($groupsales_id != null && $groupsales_id != '')	{
			$groupsales = Mage::getModel('groupsales/groupsales')->load($groupsales_id)->getData();
		} else {
			$groupsales = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($groupsales == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$groupsalesTable = $resource->getTableName('groupsales');
			
			$select = $read->select()
			   ->from($groupsalesTable,array('groupsales_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$groupsales = $read->fetchRow($select);
		}
		Mage::register('groupsales', $groupsales);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}