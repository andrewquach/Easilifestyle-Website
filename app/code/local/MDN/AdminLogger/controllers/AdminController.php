<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
//Controlleur pour la gestion des contacts
class MDN_AdminLogger_AdminController extends Mage_Adminhtml_Controller_Action
{

	/**
	 * Tasks grid
	 *
	 */
	public function GridAction()
    {
    	$this->loadLayout();
        $this->renderLayout();
    }
    
    public function ClearAction()
    {
    	//clear logs
		Mage::getResourceModel('AdminLogger/Log')->TruncateTable();    	
    	
    	//confirme
    	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Logs clear'));
    	
    	//Redirect
    	$this->_redirect('AdminLogger/Admin/Grid');

    }
    
    public function PruneAction()
    {
    	//prune logs
		$pruneDelay = mage::getStoreConfig('AdminLogger/general/auto_prune_delay');
		Mage::getResourceModel('AdminLogger/Log')->Prune($pruneDelay);    	    	
    	
    	//confirme
    	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Logs successfully pruned'));
    	
    	//Redirect
    	$this->_redirect('AdminLogger/Admin/Grid');
    	
    }
    

}