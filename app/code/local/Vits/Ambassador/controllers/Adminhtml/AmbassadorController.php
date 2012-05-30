<?php

class Vits_Ambassador_Adminhtml_AmbassadorController extends Mage_Adminhtml_Controller_action 
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('ambassador/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('ambassador/ambassador')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('ambassador_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('ambassador/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('ambassador/adminhtml_ambassador_edit'))
				->_addLeft($this->getLayout()->createBlock('ambassador/adminhtml_ambassador_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('ambassador/ambassador');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('ambassador/ambassador');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $ambassadorIds = $this->getRequest()->getParam('ambassador');
        if(!is_array($ambassadorIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($ambassadorIds as $ambassadorId) {
                    $ambassador = Mage::getModel('ambassador/ambassador')->load($ambassadorId);
                    $ambassador->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ambassadorIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $ambassadorIds = $this->getRequest()->getParam('ambassador');
        if(!is_array($ambassadorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($ambassadorIds as $ambassadorId) {
                    $ambassador = Mage::getSingleton('ambassador/ambassador')
                        ->load($ambassadorId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($ambassadorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'ambassador.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_ambassador_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'ambassador.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_ambassador_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
 	public function distributeCompanyAction()
    {
    	$return = $this->totalPercentCompanyPool();
    	if($return == -1)
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Company pool value is zero. Cannot distribute it.'));
    	}
    	elseif ($return == -2)
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot find Company Pool record to distribute!'));
    	}
    	else 
    	{
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Company Pool was distributed. Have %s user receive bonus Company Pool BV.',$return));
    	}
    	$this->_redirectReferer();
    }
    
	public function totalPercentCompanyPool(){
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSale = $this->totalGroupSaleBvCompanyPool();
		//$totalCompanyPool = $this->getArrayCompanyPoolGroupByMonth();
		$totalCompanyPool = $this->getEditCompanyPool($date);
		if($totalCompanyPool == 0)
		{
			return -1;
			
		}
		elseif ($totalCompanyPool == -1)
		{
			return -2;
			
		}
		else 
		{
			$num_distributed = 0;
			
			$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
			$customer_ids = array();
			foreach ($customer_data as $data):
				$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
			endforeach;
			
			foreach($listCustomerId as $k=>$v){
				if($this->checkMinGroupSaleBVCompanyPool($v) == true){
					$groupSale = $this->groupSaleInMonthFinal($v);
					if($totalGroupSale != 0)
						$percent = $groupSale / $totalGroupSale;
					else 
						$percent = 0;
					$payCompanyBV = $percent * $totalCompanyPool;
					
					if($payCompanyBV > 0){
						$companyDistribute = Mage::getModel('ambassador/companyDistribute')->setId(null);
						$companyDistribute->setCustomerId($v);
						
						if(isset($customer_ids[$v]))
						{
							$companyDistribute->setName($customer_ids[$v]);
						}
						else 
						{
							$companyDistribute->setName("");
						}
						
						$companyDistribute->setDate(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
						$companyDistribute->setCreatedAt(date('Y-m-d'));
						$companyDistribute->setCompanyBv($payCompanyBV);
						$companyDistribute->setGroupsales($groupSale);
						
						$commission = 0.0;
						$total = $payCompanyBV;
						switch($groupSale){
							case($groupSale <= 1000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/below1000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 1001 && $groupSale <= 4000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from1001to4000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 4001 && $groupSale <= 10000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from4001to10000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 10001 && $groupSale <= 18000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from10001to18000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 18001 && $groupSale <= 28000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from18001to28000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 28001 && $groupSale <= 40000):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from28001to40000'));
								$commission = $total * $per /100;
							break;
							
							case($groupSale >= 40001):
								$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/above40001'));
								$commission = $total * $per /100;
							break;
						}
						
						$companyDistribute->setBonus($commission);
												
						$companyDistribute->save();
						//$this->updatetoGroupSale($v,$date,$payCompanyBV);
						
						
						
						$trans = Mage::getModel('transactions/transactions')->setId(null);
						$trans->setCustomerId($v);
						
						if(isset($customer_ids[$v]))
						{
							$trans->setName($customer_ids[$v]);
						}
						else 
						{
							$trans->setName("");
						}
						
						
						$trans->setTransactionsTime($date);
						$trans->setCreatedTime(date('Y-m-d H:i:s'));
						$trans->setAmount($commission);
						$trans->setStatus('enable');
						$trans->setType('Company Pool Distribution');
						$trans->setDescription('Earn from Company Pool - '.$date);
						$trans->save();
						
						$num_distributed++;
						
						
						$resource = Mage::getSingleton('core/resource');
						$read= $resource->getConnection('core_read');
						$commissionsTable = $resource->getTableName('commissions');
						$sql = 'UPDATE  '.$commissionsTable.' SET company_pool = '.$commission.' WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
						$select = $read->query($sql);
						
						$sql = 'UPDATE  '.$commissionsTable.' SET total_income = affiliate + orphan_pool + company_pool + commission WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
						$select = $read->query($sql);
						
						$sql = 'UPDATE  '.$commissionsTable.' SET subtotal = total_income + rebate WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
						$select = $read->query($sql);
						
					}
				} 	
			}
			$this->updateStatusDistributeCompanyPool($date);
			return $num_distributed;
		}
    }
    
	public function totalGroupSaleBvCompanyPool(){
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSaleBV=0;
		$tmp = 0;
		//$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		foreach($listCustomerId as $k=>$v){
			if($this->checkMinGroupSaleBVCompanyPool($v) == true){
				$tmp = $this->groupSaleInMonthFinal($v);
				$totalGroupSaleBV += $tmp;
			}
		}
		
		return $totalGroupSaleBV;
    }
 /**
     * ***********************************************
     * Ham lay gia tri company pool can chia, neu khong co, tra ve -1
     * @param unknown_type $date
     * @return unknown
     */
    public function getEditCompanyPool($date)
    {
    	$company_pool = Mage::getModel('companypool/companypool')->getCollection();
    	$company_pool->getSelect()->where('company_time = ?',$date)->where('status <> \'distributed\'');
    	$company_pool_data = $company_pool->getData();
    	if(isset($company_pool_data[0]['company_edit_amount']))
    	{
    		return $company_pool_data[0]['company_edit_amount'];
    	}
    	else
    	{
    		return -1;
    	}
    }
    /**
     * ******************************
     * Ham update status cua company pool len distributed neu no duoc distribute
     * @param unknown_type $date
     */
    public function updateStatusDistributeCompanyPool($date)
    {
    	$company_pool = Mage::getModel('companypool/companypool')->getCollection();
    	$company_pool->getSelect()->where('company_time = ?',$date)->where('status <> \'distributed\'');
    	$company_pool_data = $company_pool->getData();
    	if(isset($company_pool_data[0]['company_edit_amount']) && $company_pool_data[0]['company_edit_amount'] != 0)
    	{
    		$company_pool_model = Mage::getModel('companypool/companypool')->load($company_pool_data[0]['companypool_id']);
    		$company_pool_model->setStatus('distributed');
    		$company_pool_model->save();
    	}
    }
  /**
     * Ham tra ve total Company Pool on month cua tat ca cac Ambassador khong duoc active
	**/
    public function getArrayCompanyPoolGroupByMonth(){
		$listCustomerId = $this->getIdListByRole('ambassador');
		$totalCompanyPool = 0;
		foreach($listCustomerId as $k=>$v){
			if($this->checkActive($v)== false){
				$totalCompanyPool += $this->groupSaleInMonth($v);
			}
		}
		return $totalCompanyPool;
    }
     public function checkMinGroupSaleBVCompanyPool($id=null){
		$groupSales = $this->groupSaleInMonthFinal($id);
		$mingroupsaleCompanyPool = Mage::getStoreConfig('admin/usezcommon/bvcompanypool');
		if(isset($groupSales) && $groupSales >= $mingroupsaleCompanyPool)
			return true;
		return false;
    }
    
	public function distributeOrphanAction()
    {
    	$return = $this->totalPercentOrphanPool();
    	if($return == -1)
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Walk-in-Pool value is zero. Cannot distribute it.'));
    	}
    	elseif ($return == -2)
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot find Walk-in-Pool record to distribute!'));
    	}
    	else 
    	{
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Walk-in-Pool was distributed. Have %s user receive bonus Walk-in-Pool BV.',$return));
    	}
    	$this->_redirectReferer();
    }
	
   
 /** Orphan Distribute **/
    
	public function totalPercentOrphanPool(){
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSale = $this->totalGroupSaleBv();
		$totalOprhanPool = $this->getEditOrphanPool($date);
		if($totalOprhanPool == 0)
		{
			return -1;
			
		}
		elseif ($totalOprhanPool == -1)
		{
			return -2;
			
		}
		else 
		{
			
			$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
			$customer_ids = array();
			foreach ($customer_data as $data):
				$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
			endforeach;
			
			$num_distributed = 0;
			foreach($listCustomerId as $k=>$v){
				if($this->checkMinGroupSaleBVOrphanPool($v) == true){
					$groupSale = $this->groupSaleInMonthFinal($v);
					if($totalGroupSale != 0)
						$percent = $groupSale / $totalGroupSale;
					else 
						$percent = 0;
					$payOrphanBV = $percent * $totalOprhanPool;
					
					if($payOrphanBV > 0){
						$check_time = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
						$orphan_collection = Mage::getModel('ambassador/orphanDistribute')->getCollection();
						$orphan_collection->getSelect()->where('customer_id = ?',$v)->where('date = ?',$check_time);
						$orphan_data = $orphan_collection->getData();
						
						if(count($orphan_data) > 0)
						{
							
						}
						else 
						{
							
							$orphanDistribute = Mage::getModel('ambassador/orphanDistribute')->setId(null);
							$orphanDistribute->setCustomerId($v);
							
							if(isset($customer_ids[$v]))
							{
								$orphanDistribute->setName($customer_ids[$v]);
							}
							else 
							{
								$orphanDistribute->setName("");
							}
							
							$orphanDistribute->setDate(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
							$orphanDistribute->setCreatedAt(date('Y-m-d'));
							$orphanDistribute->setOrphanBv($payOrphanBV);
							$orphanDistribute->setGroupsales($groupSale);
							
							$commission = 0.0;
							$total = $payOrphanBV;
							switch($groupSale){
								case($groupSale <= 1000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/below1000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 1001 && $groupSale <= 4000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from1001to4000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 4001 && $groupSale <= 10000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from4001to10000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 10001 && $groupSale <= 18000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from10001to18000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 18001 && $groupSale <= 28000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from18001to28000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 28001 && $groupSale <= 40000):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from28001to40000'));
									$commission = $total * $per /100;
								break;
								
								case($groupSale >= 40001):
									$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/above40001'));
									$commission = $total * $per /100;
								break;
							}
							
							$orphanDistribute->setBonus($commission);
							$orphanDistribute->save();
							
							$trans = Mage::getModel('transactions/transactions')->setId(null);
							$trans->setCustomerId($v);
							
							if(isset($customer_ids[$v]))
							{
								$trans->setName($customer_ids[$v]);
							}
							else 
							{
								$trans->setName("");
							}
							
							$trans->setTransactionsTime($date);
							$trans->setCreatedTime(date('Y-m-d H:i:s'));
							$trans->setAmount($commission);
							$trans->setStatus('enable');
							$trans->setType('Walk-in Pool Distribution');
							$trans->setDescription('Earn from Walk-in Pool - '.$date);
							$trans->save();
							
							
							//$this->updatetoGroupSale($v,$date,$payOrphanBV);
							$num_distributed++;
							
							$resource = Mage::getSingleton('core/resource');
							$read= $resource->getConnection('core_read');
							$commissionsTable = $resource->getTableName('commissions');
							$sql = 'UPDATE  '.$commissionsTable.' SET orphan_pool = '.$commission.' WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
							$select = $read->query($sql);
							
							$sql = 'UPDATE  '.$commissionsTable.' SET total_income = affiliate + orphan_pool + company_pool + commission WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
							$select = $read->query($sql);
							
							$sql = 'UPDATE  '.$commissionsTable.' SET subtotal = total_income + rebate WHERE customer_id = '.$v.' AND commission_time = \''.$date.'\';';
							$select = $read->query($sql);		
						}
					}
				} 	
			}
			$this->updateStatusDistributeOrphanPool($date);
			return $num_distributed;
		}
    }
    /**
     * *****************************************************
     * Ham lay gia tri Orphan Pool can chia, neu khong co, tra ve -1
     * @param unknown_type $date
     * @return unknown
     */
	public function getEditOrphanPool($date)
    {
    	$orphan_pool = Mage::getModel('orphanpool/orphanpool')->getCollection();
    	$orphan_pool->getSelect()->where('orphan_time = ?',$date)->where('status <> \'distributed\'');
    	$orphan_pool_data = $orphan_pool->getData();
    	if(isset($orphan_pool_data[0]['orphan_edit_amount']))
    	{
    		return $orphan_pool_data[0]['orphan_edit_amount'];
    	}
    	else
    	{
    		return -1;
    	}
    }
/**
     * ******************************
     * Ham update status cua orphan pool len distributed neu no duoc distribute
     * @param unknown_type $date
     */
    public function updateStatusDistributeOrphanPool($date)
    {
    	$orphan_pool = Mage::getModel('orphanpool/orphanpool')->getCollection();
    	$orphan_pool->getSelect()->where('orphan_time = ?',$date)->where('status <> \'distributed\'');
    	$orphan_pool_data = $orphan_pool->getData();
    	if(isset($orphan_pool_data[0]['orphan_edit_amount']) && $orphan_pool_data[0]['orphan_edit_amount'] != 0)
    	{
    		$orphan_pool_model = Mage::getModel('orphanpool/orphanpool')->load($orphan_pool_data[0]['orphanpool_id']);
    		$orphan_pool_model->setStatus('distributed');
    		$orphan_pool_model->save();
    	}
    }
    
	public function getIdListByRole($role='')
	{
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id   FROM '.$customerTable.'
		WHERE user_role = \''.$role.'\' ';  
		$select = $read->query($sql);
		$listCustomerId = $select->fetchAll();
		$list_ids = array();
		foreach($listCustomerId as $k=>$v)
		{
			$list_ids[] = $v['entity_id'];
		}
		return $list_ids;
	}
	public function totalGroupSaleBv(){
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSaleBV=0;
		$tmp = 0;
		//$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		foreach($listCustomerId as $k=>$v){
			if($this->checkMinGroupSaleBVOrphanPool($v) == true){
				$tmp = $this->groupSaleInMonthFinal($v);
				$totalGroupSaleBV += $tmp;
			}
		}
		return $totalGroupSaleBV;
    }
	public function getTotalOrphanPool(){
    	$from = date('Y-m', strtotime('-1 months')).'-01';
		$to = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT bv_parent_id  , SUM(bv) FROM '.$bvTable.'
		WHERE bv_parent_id = 0 AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY bv_parent_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		if(isset($data[0]['SUM(bv)']))
			return $data[0]['SUM(bv)'];
		return 0;
    }
	/*
    public function totalGroupSaleBvById($id=null){
		$groupSales = $this->groupSaleInMonth($id);
		$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		
		if(isset($groupSales) && $groupSales >= $mingroupsale)
			return $groupSales;
		return 0;
	} 
	*/
   
	public function checkMinGroupSaleBVOrphanPool($id=null){
		$groupSales = $this->groupSaleInMonthFinal($id);
		$mingroupsaleorphanPool = Mage::getStoreConfig('admin/usezcommon/bvorphanpool');
		
		if(isset($groupSales) && $groupSales >= $mingroupsaleorphanPool)
			return true;
		return false;
		
	}
/**
    * *********************************************************
    * Ham cong them BV cho Ambassador, BV phat sinh tu company hay orphan Pool
    * @param unknown_type $id
    * @param unknown_type $date
    * @param unknown_type $bv
    */ 
	public function updatetoGroupSale($id=null, $date=null, $bv=null){
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('groupsales');
		$sql = 'SELECT *   FROM '.$customerTable.'
		WHERE customer_id = \''.$id.'\' AND  groupsales_month = \''.$date.'\' ';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
		if(isset($list[0]['groupsales_id']) && $list[0]['groupsales_id'] != null){
			$totalAfterGroupsale = $list[0]['after_groupsales'] + $bv;
			$group = Mage::getModel('groupsales/groupsales')->setId($list[0]['groupsales_id']);
	    	$group->setCustomerId($list[0]['customer_id']);
	    	$group->setAfterGroupsales($totalAfterGroupsale);
	    	$group->setUpdatedTime(date('Y-m-d H:i:s'));
	    	$group->setNotes('Update bv for customer id = '.$list[0]['customer_id']);
	    	$group->setStatus('new');
	    	$group->save();
		}
     }
	public function updatetoUsedGroupSale($id=null, $date=null){
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('groupsales');
		$sql = 'SELECT *   FROM '.$customerTable.'
		WHERE customer_id = \''.$id.'\' AND  groupsales_month = \''.$date.'\' ';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
		if(isset($list[0]['groupsales_id']) && $list[0]['groupsales_id'] != null){
			$group = Mage::getModel('groupsales/groupsales')->setId($list[0]['groupsales_id']);
	    	$group->setCustomerId($list[0]['customer_id']);
	    	//$group->setAfterGroupsales(0);
	    	$group->setUpdatedTime(date('Y-m-d H:i:s'));
	    	$group->setNotes('Update bv for customer id = '.$list[0]['customer_id']);
	    	$group->setStatus('used');
	    	$group->save();
		}
     }
     
	public function groupSaleInMonth($id=null){
		$from = date('Y-m', strtotime('-1 months')).'-01';
		$to = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT bv_parent_id  , SUM(bv) FROM '.$bvTable.'
		WHERE bv_parent_id = '.$id. ' AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY bv_parent_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();	
		
		if(isset($data[0]['SUM(bv)']))
			return $data[0]['SUM(bv)'];
		return 0;
	}
	public function distributeCommissionAction()
	{
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		if(!$this->checkCompanyDistributed($date) || !$this->checkOrphanDistributed($date))
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot distribute Commission!'));
			if(!$this->checkCompanyDistributed($date))
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Please distributed Company Pool before distribute Commission!'));
			}
			if(!$this->checkOrphanDistributed($date))
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Please distributed Walk-in-Pool before distribute Commission!'));
			}
			$this->_redirectReferer();
			return;
		}
		$this->earnFromGroupsalesGL();
		$return = $this->earnFromGroupsales();
		$this->saveSeniorAffCommission();
		if($return != 0)
		{
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Commission was distributed. Have %s user receive commission in this Distributed.',$return));
		}
		else 
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot distribute Commission! Because one or more follow reason happend: <br /> 1. No user can receive commission. <br />2. Commission for this month was distributed.'));
		}
		$this->_redirectReferer();
	}
	public function checkOrphanDistributed($date = null)
	{
		$orphan_pool = Mage::getModel('orphanpool/orphanpool')->getCollection();
    	$orphan_pool->getSelect()->where('orphan_time = ?',$date)->where('status = \'distributed\'');
    	$orphan_pool_data = $orphan_pool->getData();
    	if(count($orphan_pool_data) > 0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    	
	}
	public function checkCompanyDistributed($date = null)
	{
		$company_pool = Mage::getModel('companypool/companypool')->getCollection();
    	$company_pool->getSelect()->where('company_time = ?',$date)->where('status = \'distributed\'');
    	$company_pool_data = $company_pool->getData();
    	if(count($company_pool_data) > 0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
	}
/* *****************************************************
    * Ham chia loi nhuan bang tien cho Ambassador, sau khi da cong them company Pool va Walk-in-Pool
    */
    public function earnFromGroupsales(){
    	$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('groupsales');
		$sql = 'SELECT customer_id ,after_groupsales   FROM '.$customerTable.'
		WHERE groupsales_month = \''.$date.'\' AND status != \'used\'';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
		$num_distribute = 0;
		
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
		$customer_ids = array();
		foreach ($customer_data as $data):
			$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
		endforeach;
		
		foreach($list as $k=>$v){
			$total=$v['after_groupsales'];
			
	   		$commission = 0.0;
			
	   		switch($total){
	    		case($total <= 1000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/below1000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 1001 && $total <= 4000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from1001to4000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 4001 && $total <= 10000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from4001to10000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 10001 && $total <= 18000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from10001to18000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 18001 && $total <= 28000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from18001to28000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 28001 && $total <= 40000):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/from28001to40000'));
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 40001):
	    			$per = floatval(Mage::getStoreConfig('admin/groupsalecommission/above40001'));
	    			$commission = $total * $per /100;
	    		break;
	    	}
	    	
	    	//$commission = number_format($commission, 2);
	    	if($commission > 0){
		    	$trans = Mage::getModel('transactions/transactions')->setId(null);
	    		$trans->setCustomerId($v['customer_id']);		
				
				if(isset($customer_ids[$v['customer_id']]))
				{
					$trans->setName($customer_ids[$v['customer_id']]);
				}
				else 
				{
					$trans->setName("");
				}
				
	    		$trans->setTransactionsTime($date);
	    		$trans->setCreatedTime(date('Y-m-d H:i:s'));
	    		$trans->setAmount($commission);
	    		$trans->setStatus('enable');
	    		$trans->setType('commission');
	    		$trans->setDescription('Earn from Group Sales - '.$date);
	    		$trans->save();
	    		$num_distribute++;
	    		$this->updatetoUsedGroupSale($v['customer_id'],$date);
				
				
				$resource = Mage::getSingleton('core/resource');
				$read= $resource->getConnection('core_read');
				$commissionsTable = $resource->getTableName('commissions');
				$sql = 'UPDATE  '.$commissionsTable.' SET commission = '.$commission.' WHERE customer_id = '.$v['customer_id'].' AND commission_time = \''.$date.'\';';
				$select = $read->query($sql);
				
				$sql = 'UPDATE  '.$commissionsTable.' SET total_income = affiliate + orphan_pool + company_pool + commission WHERE customer_id = '.$v['customer_id'].' AND commission_time = \''.$date.'\';';
				$select = $read->query($sql);
				
				$sql = 'UPDATE  '.$commissionsTable.' SET subtotal = total_income + rebate WHERE customer_id = '.$v['customer_id'].' AND commission_time = \''.$date.'\';';
				$select = $read->query($sql);
				
	    	}
		}
		return $num_distribute;
    }
    
    
/**
    * ******************************************
    * Ham chia loi nhuan bang GL Point cho Ambassador
    */
    public function earnFromGroupsalesGL(){
   		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$groupsaleTable = $resource->getTableName('groupsales');
		$sql = 'SELECT customer_id ,after_groupsales   FROM '.$groupsaleTable.'
		WHERE groupsales_month = \''.$date.'\' AND status != \'used\'';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
			
   		foreach($list as $k=>$v){
			
			$total=$v['after_groupsales'];
	   		switch($total){
	    		case($total <= 1000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/below1000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 1001 && $total <= 4000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from1001to4000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 4001 && $total <= 10000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from4001to10000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 10001 && $total <= 18000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from10001to18000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 18001 && $total <= 28000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from18001to28000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 28001 && $total <= 40000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from28001to40000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 40001):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/above40001');
	    			$commission = $total * $per /100;
	    		break;
	    	}
	    	
	    	$customer_data=Mage::getResourceModel('customer/customer_collection')
	        ->addAttributeToSelect('firstname')
	        ->addAttributeToSelect('lastname');
	      
		    $customer_ids = array();
		    $firstname = '';
		    $lastname = '';
		    foreach ($customer_data as $data):
		    	if($data->getId()==$v['customer_id']){
		    		$firstname = $data->getFirstname();
		    		$lastname = $data->getLastname();
		    	}
		    endforeach;
	    
		    $expi = Mage::getStoreConfig('admin/usezcommon/glexpiration');
	    	if($commission > 0){
		    	$groupgl = Mage::getModel('ambassador/groupgl')->setId(null);
	    		$groupgl->setCustomerId($v['customer_id']);
	    		$groupgl->setCustomerFirstname($firstname);
	    		$groupgl->setCustomerLastname($lastname);
	    		$groupgl->setCreateDate(date('Y-m-d'));
	    		$groupgl->setGlExpirationDate(date('Y-m-d',strtotime('+ '.$expi.' years - 1 days')));
	    		$groupgl->setGl($commission);
	    		$groupgl->setStatus('unused');
	    		$groupgl->setOrderId('Group Sales Bonus');
	    		$groupgl->save();
	    	}
		}
    }
	public function cashRebateAction()
	{
		$return = $this->saveAllCashRebate();
		if($return == 0)
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot distribute Commission! Because one or more follow reason happend: <br /> 1. No user have enough bv to receive Cash Rebate. <br />2. All Cash for this month was rebated.'));
			
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Cash Rebate return successful! Have %s user receive cash rebate.',$return));
			
		}
		$this->_redirectReferer();
	}
	public function saveAllCashRebate()
	{
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id   FROM '.$customerTable.'
		WHERE user_role = \'ambassador\' OR  user_role = \'seniorambassador\'';  
		$select = $read->query($sql);
		$listCustomerId = $select->fetchAll();
		$num_rebate = 0;
		
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
		$customer_ids = array();
		foreach ($customer_data as $data):
			$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
		endforeach;
		
		foreach($listCustomerId as $k=>$v)
		{
			if($this->saveCashRebateForOneCustomer($v['entity_id'],$date,$customer_ids))
				$num_rebate++;
		}
		
		
		
		return $num_rebate;
		
	}
	public function saveCashRebateForOneCustomer($id = null,$date = null,$customer_ids=array())
	{
	
		$min_bv = Mage::getStoreConfig('admin/usezcommon/bvrebate');
		$rebate_percent = Mage::getStoreConfig('admin/usezcommon/percentrebate');
		$month_bv = $this->groupBVInMonthByCustomerId($id);
		$amount_rebate = $month_bv-$min_bv;
		if($amount_rebate > 0)
		{
			$transactions = Mage::getModel('transactions/transactions')->getCollection();
			$transactions->getSelect()->where('customer_id = ?',$id)->where('transactions_time = ?',$date)->where('type = \'rebate\'');
			$transactions_data = $transactions->getData();
			if(count($transactions_data) == 0)
			{
				$amount = $amount_rebate*$rebate_percent/100;
				$transaction_model = Mage::getModel('transactions/transactions')->setId(null);
				$transaction_model->setCustomerId($id);
				
				if(isset($customer_ids[$id]))
				{
					$transaction_model->setName($customer_ids[$id]);
				}
				else 
				{
					$transaction_model->setName("");
				}
				
	    		$transaction_model->setTransactionsTime($date);
	    		$transaction_model->setCreatedTime(date('Y-m-d H:i:s'));
	    		$transaction_model->setAmount($amount);
	    		$transaction_model->setStatus('enable');
	    		$transaction_model->setType('Rebate');
	    		$transaction_model->setDescription('Cash rebate - '.$date);
	    		$transaction_model->save();
				
				$resource = Mage::getSingleton('core/resource');
				$read= $resource->getConnection('core_read');
				$commissionsTable = $resource->getTableName('commissions');
				$sql = 'UPDATE  '.$commissionsTable.' SET rebate = '.$amount.' WHERE customer_id = '.$id.' AND commission_time = \''.$date.'\';';
				$select = $read->query($sql);
												
				$sql = 'UPDATE  '.$commissionsTable.' SET subtotal = total_income + rebate WHERE customer_id = '.$id.' AND commission_time = \''.$date.'\';';
				$select = $read->query($sql);
				
				
	    		return true;
			}
    		
			
		}
		return false;
	}
	/**
	 * *********************************************
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
	public function groupBVInMonthByCustomerId($id=null){
		$from = date('Y-m', strtotime('-1 months')).'-01';
		$to = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT customer_id  , SUM(bv) FROM '.$bvTable.'
		WHERE customer_id = '.$id. ' AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		
		if(isset($data[0]['SUM(bv)']))
			return $data[0]['SUM(bv)'];
		return 0;
	}
	
 /**
     * Ham save theo tung thang commisson luu vao transaction cho tat Senior Ambassador
     * Luu commission cho SeniorAmbassador co duoc tu Affiliate Merchant groupsale.
	**/
	
    public function distributeAffiliateAction(){
		$return = $this->distributeAffiliate();
    	if ($return == -1)
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ambassador')->__('Cannot find Affiliate Merchant records to distribute!'));
    	}
    	else 
    	{
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Affiliate Merchant Commissions were distributed. Have %s affiliator receive affiliate commission.',$return));
    	}
    	$this->_redirectReferer();
    }
    
	
	public function distributeAffiliate(){
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		
    	$affiliatecommission = Mage::getModel('affiliatecommission/affiliatecommission')->getCollection();
    	$affiliatecommission->getSelect()->where('affiliate_time = ?',$date)->where('status <> \'distributed\'');
    	$affiliatecommission_data = $affiliatecommission->getData();    	

		// $listCustomerId = $this->getIdListByRole('seniorambassador');
		// $totalGroupSale = $this->totalGroupSaleBvCompanyPool();
		// $totalCompanyPool = $this->getArrayCompanyPoolGroupByMonth();
		
		$numOfAffilateCommission = count($affiliatecommission_data);
		
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
		$customer_ids = array();
		foreach ($customer_data as $data):
			$customer_ids[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
		endforeach;
		
		
		if($numOfAffilateCommission == 0)
		{
			return -1;			
		}		
		else 
		{		
			foreach($affiliatecommission_data as $data){
				$personalBV = $this->groupBVInMonthByCustomerId($data['customer_id']);
				$min_bv = Mage::getStoreConfig('admin/usezcommon/bvrebate');
				if ($personalBV >= $min_bv){
					$customer_id = $data['customer_id'];
					$affiliateBV = $data['affiliate_bv'];
					$groupsales = $data['groupsales'];
					$commission = $this->payAffiliateMerchant($customer_id,$groupsales,$affiliateBV);
					
					
					$resource = Mage::getSingleton('core/resource');
					$read= $resource->getConnection('core_read');
					$commissionsTable = $resource->getTableName('commissions');
					$sql = 'UPDATE  '.$commissionsTable.' SET affiliate = '.$commission.' WHERE customer_id = '.$customer_id.' AND commission_time = \''.$date.'\';';
					$select = $read->query($sql);
					
					$sql = 'UPDATE  '.$commissionsTable.' SET total_income = affiliate + orphan_pool + company_pool + commission WHERE customer_id = '.$customer_id.' AND commission_time = \''.$date.'\';';
					$select = $read->query($sql);
					
					$sql = 'UPDATE  '.$commissionsTable.' SET subtotal = total_income + rebate WHERE customer_id = '.$customer_id.' AND commission_time = \''.$date.'\';';
					$select = $read->query($sql);
					
					$resource = Mage::getSingleton('core/resource');
					$read= $resource->getConnection('core_read');
					$affiliatecommissionTable = $resource->getTableName('affiliatecommission');
					$sql = 'UPDATE  '.$affiliatecommissionTable.' SET affiliatecommission = '.$commission.' , status = \'distributed\' WHERE customer_id = '.$customer_id.' AND affiliate_time = \''.$date.'\';';
					$select = $read->query($sql);				
					
					
					$trans = Mage::getModel('transactions/transactions')->setId(null);
					$trans->setCustomerId($data['customer_id']);
					
					if(isset($customer_ids[$data['customer_id']]))
					{
						$trans->setName($customer_ids[$data['customer_id']]);
					}
					else 
					{
						$trans->setName("");
					}
					
					
					$trans->setTransactionsTime($date);
					$trans->setCreatedTime(date('Y-m-d H:i:s'));
					$trans->setAmount($commission);
					$trans->setStatus('enable');
					$trans->setType('Affiliate Commission');
					$trans->setDescription('Earn from Affiliate Commission - '.$date);
					$trans->save();
				}
				else
				{
					$customer_id = $data['customer_id'];
					$resource = Mage::getSingleton('core/resource');
					$read= $resource->getConnection('core_read');
					$affiliatecommissionTable = $resource->getTableName('affiliatecommission');
					$sql = 'UPDATE  '.$affiliatecommissionTable.' SET status = \'distributed\' WHERE customer_id = '.$customer_id.' AND affiliate_time = \''.$date.'\';';
					$select = $read->query($sql);
				}
			}		
			return $numOfAffilateCommission;
		}
	}
	
    /**
     * Ham tinh commission cho mot Senior Ambassador
	**/
    
    public function payAffiliateMerchant($id, $total, $totalAffiliate){    	
    	$per = 0;
    	$commssion=0;
    	switch($total){
			case($total <= 1000):
    			$per = Mage::getStoreConfig('admin/affiliate/below1000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
		
    		case($total >= 1001 && $total <= 4000):
    			$per = Mage::getStoreConfig('admin/affiliate/from1001to4000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 4001 && $total <= 10000):
    			$per = Mage::getStoreConfig('admin/affiliate/from4001to10000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 10001 && $total <= 18000):
    			$per = Mage::getStoreConfig('admin/affiliate/from10001to18000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 18001 && $total <= 28000):
    			$per = Mage::getStoreConfig('admin/affiliate/from18001to28000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 28001 && $total <= 40000):
    			$per = Mage::getStoreConfig('admin/affiliate/from28001to40000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 40001):
    			$per = Mage::getStoreConfig('admin/affiliate/above40001');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    	}
    	return $commssion;    	
    }
    
    
    /**
     * Ham tinh GroupSale Affiliate cua mot Senior Ambassador
     * Tra ve tong Group Sale cua cac Affiliate la con cua Ambassador cos id la $id.
	**/
    
    public function totalGroupSaleAffiliate($id=null){
    	$listAffiliate = $this->listAffiliate($id);
    	$totalGroupSale = 0;
    	foreach($listAffiliate as $k=>$v){
    		$totalGroupSale += $this->groupSaleInMonth($v);
    	}
    	return $totalGroupSale;
    }
    
    /**
     * Ham tra ve array id cua Affiliate la con level 1 cua Senior Ambass co is la $id
	**/
    
    public function listAffiliate($id=null){
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id	 FROM '.$customerTable.'
		WHERE parent_id =  \''.$id.'\' AND is_affiliate LIKE \''."yes".'\' 
		GROUP BY entity_id';
		
		$select = $read->query($sql);
		$data = $select->fetchAll();
		$list_ids = array();
		foreach($data as $k=>$v)
		{
			$list_ids[] = $v['entity_id'];
		}
		return $list_ids;
    }
    
    public function groupSaleInMonthFinal($id=null){
		$totalGroupsale = $this->groupSaleInMonth($id);
		$personalBV = $this->groupBVInMonthByCustomerId($id);
		$min_bv = Mage::getStoreConfig('admin/usezcommon/bvrebate');
		$total = floatval(0);
		if ($personalBV >= $min_bv){
			$total = floatval($totalGroupsale+$personalBV-$min_bv);			
		}
		return $total;	
	}
	
}