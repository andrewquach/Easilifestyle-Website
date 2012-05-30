<?php
class Vits_Affiliatecommission_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/affiliatecommission?id=15 
    	 *  or
    	 * http://site.com/affiliatecommission/id/15 	
    	 */
    	/* 
		$affiliatecommission_id = $this->getRequest()->getParam('id');

  		if($affiliatecommission_id != null && $affiliatecommission_id != '')	{
			$affiliatecommission = Mage::getModel('affiliatecommission/affiliatecommission')->load($affiliatecommission_id)->getData();
		} else {
			$affiliatecommission = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($affiliatecommission == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$affiliatecommissionTable = $resource->getTableName('affiliatecommission');
			
			$select = $read->select()
			   ->from($affiliatecommissionTable,array('affiliatecommission_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$affiliatecommission = $read->fetchRow($select);
		}
		Mage::register('affiliatecommission', $affiliatecommission);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}