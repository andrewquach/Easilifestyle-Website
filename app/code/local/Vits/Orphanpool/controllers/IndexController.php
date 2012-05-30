<?php
class Vits_Orphanpool_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/orphanpool?id=15 
    	 *  or
    	 * http://site.com/orphanpool/id/15 	
    	 */
    	/* 
		$orphanpool_id = $this->getRequest()->getParam('id');

  		if($orphanpool_id != null && $orphanpool_id != '')	{
			$orphanpool = Mage::getModel('orphanpool/orphanpool')->load($orphanpool_id)->getData();
		} else {
			$orphanpool = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($orphanpool == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$orphanpoolTable = $resource->getTableName('orphanpool');
			
			$select = $read->select()
			   ->from($orphanpoolTable,array('orphanpool_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$orphanpool = $read->fetchRow($select);
		}
		Mage::register('orphanpool', $orphanpool);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}