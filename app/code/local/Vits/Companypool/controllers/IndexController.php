<?php
class Vits_Companypool_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/companypool?id=15 
    	 *  or
    	 * http://site.com/companypool/id/15 	
    	 */
    	/* 
		$companypool_id = $this->getRequest()->getParam('id');

  		if($companypool_id != null && $companypool_id != '')	{
			$companypool = Mage::getModel('companypool/companypool')->load($companypool_id)->getData();
		} else {
			$companypool = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($companypool == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$companypoolTable = $resource->getTableName('companypool');
			
			$select = $read->select()
			   ->from($companypoolTable,array('companypool_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$companypool = $read->fetchRow($select);
		}
		Mage::register('companypool', $companypool);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}