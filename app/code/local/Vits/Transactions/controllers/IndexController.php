<?php
class Vits_Transactions_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/transactions?id=15 
    	 *  or
    	 * http://site.com/transactions/id/15 	
    	 */
    	/* 
		$transactions_id = $this->getRequest()->getParam('id');

  		if($transactions_id != null && $transactions_id != '')	{
			$transactions = Mage::getModel('transactions/transactions')->load($transactions_id)->getData();
		} else {
			$transactions = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($transactions == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$transactionsTable = $resource->getTableName('transactions');
			
			$select = $read->select()
			   ->from($transactionsTable,array('transactions_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$transactions = $read->fetchRow($select);
		}
		Mage::register('transactions', $transactions);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}