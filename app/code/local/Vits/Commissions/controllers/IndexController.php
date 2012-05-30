<?php
class Vits_Commissions_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/commissions?id=15 
    	 *  or
    	 * http://site.com/commissions/id/15 	
    	 */
    	/* 
		$commissions_id = $this->getRequest()->getParam('id');

  		if($commissions_id != null && $commissions_id != '')	{
			$commissions = Mage::getModel('commissions/commissions')->load($commissions_id)->getData();
		} else {
			$commissions = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($commissions == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$commissionsTable = $resource->getTableName('commissions');
			
			$select = $read->select()
			   ->from($commissionsTable,array('commissions_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$commissions = $read->fetchRow($select);
		}
		Mage::register('commissions', $commissions);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}