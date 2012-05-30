<?php
class Vits_Ambassador_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
   		$permission = $this->__getPermission(array('ambassador','seniorambassador'));
    	if(!$permission)
    	{
    		$this->_redirectUrl(Mage::getUrl('ambassador/index/denied'));
    	}
		else 
		{	
			$this->loadLayout();     
			$this->renderLayout();
		}
    }
	public  function deniedAction()
    { 
    	$this->loadLayout();     
		$this->renderLayout();
    	
    }
    protected function __getPermission($role=array())
    {
    	$customer = Mage::getSingleton('customer/session')->getCustomer();
        if($customer instanceof Mage_Customer_Model_Customer)
        {
        	$data = $customer->getData();
        	if(isset($data['user_role']))
        	{
        		if(in_array($data['user_role'],$role))
        		{
        			return true;
        		}
        		else 
        		{
        			return false;
        		}
        	}
        	return false;
        }
        return true;
    }
    public  function viewgroupbvAction()
    { 
    	$permission = $this->__getPermission(array('ambassador','seniorambassador'));
    	if(!$permission)
    	{
    		$this->_redirectUrl(Mage::getUrl('ambassador/index/denied'));
    	}
    	else 
    	{
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
	public  function viewgroupglAction()
    { 
    	$permission = $this->__getPermission(array('ambassador','seniorambassador'));
    	if(!$permission)
    	{
    		$this->_redirectUrl(Mage::getUrl('ambassador/index/denied'));
    	}
    	else 
    	{
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
 	public function exportGroupbvCsvAction()
    {
        $fileName   = 'groupsale.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_groupbv_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportGroupbvExcelAction()
    {
        $fileName   = 'groupsale.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_groupbv_grid')
            ->getExcel($fileName);

        $this->_sendUploadResponse($fileName, $content);
    }
	public function exportGroupglCsvAction()
    {
        $fileName   = 'groupgl.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_groupgl_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportGroupglExcelAction()
    {
        $fileName   = 'groupgl.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_groupgl_grid')
            ->getExcel($fileName);

        $this->_sendUploadResponse($fileName, $content);
    }
	public function exportAccountbvCsvAction()
    {
        $fileName   = 'accountbv.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountbv_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportAccountbvExcelAction()
    {
        $fileName   = 'accountbv.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountbv_grid')
            ->getExcel($fileName);

        $this->_sendUploadResponse($fileName, $content);
    }
	public function exportAccountglCsvAction()
    {
        $fileName   = 'accountgl.csv';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountgl_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportAccountglExcelAction()
    {
        $fileName   = 'accountgl.xml';
        $content    = $this->getLayout()->createBlock('ambassador/adminhtml_report_accountgl_grid')
            ->getExcel($fileName);

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
    public function viewbvAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
	public function viewglAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
    /**
     * ***********************************************
     * Ham nay duoc xet chay moi ngay
     *
     */
    public function crondayAction()
    {
    	//gui mail cho customer khi du dieu kien len Ambassador
    	$this->checkAllCustomerToAmbassador();
    	
    	//update data va gui mail cho Ambassador khi duoc len seniorambassador
    	$this->checkAllAmbassadorToSenior();
    }
 /**
     * ***********************************************
     * Ham nay duoc xet chay vao ngay 1 cua moi thang
     *
     */
    public function cronmonthAction()
    {
    	//Tinh GroupSale cho Ambassador va Senior,lu vao bang GroupSale
    	$this->savetoGroupSale();
    	//Tinh OrphanPool cua thang truoc
    	$this->totalOrphanPoolGroupByMonth();
    	//Tinh CompanyPool
    	$this->totalCompanyPoolGroupByMonth();
		//Tinh Affiliate Commission
    	$this->totalAffiliateCommisionGroupByMonth();
		
    	
    }
	
	/** End Orphan Distribute **/
	
    /** Total Company Pool on month
     *  Save(); 
	**/
 	public function totalAffiliateCommisionGroupByMonth(){		
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id FROM '.$customerTable.' WHERE is_affiliate = \'yes\' ';  
		$select = $read->query($sql);
		$listCustomerId = $select->fetchAll();
		$list_ids = array();
		foreach($listCustomerId as $k=>$v)
		{
			$list_ids[] = $v['entity_id'];
		}
		
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname');
      
		$names = array();
		
		foreach ($customer_data as $data){
			$names[$data->getId()] = $data->getFirstname().' '.$data->getLastname();			
		}
    	
		foreach($list_ids as $k=>$v){
			$affiliateBV = $this->affiliateBVInMonth($v);
			$groupSales = $this->groupSaleInMonthFinal($v);		
			if ($affiliateBV > 0)
			{
				$affiliatecommission = Mage::getModel('ambassador/affiliatecommission')->setId(null);
				$affiliatecommission->setAffiliateTime(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
				$affiliatecommission->setCustomerId($v);
				$affiliatecommission->setName($names[$v]);    		
				$affiliatecommission->setAffiliateBv($affiliateBV);
				$affiliatecommission->setGroupsales($groupSales);				
				$affiliatecommission->setCreatedTime(date('Y-m-d'));
				$affiliatecommission->setNotes('Total Affiliate Commission in month of customer id '.$v);
				$affiliatecommission->save();
			}
    	}
    }
	
	public function affiliateBVInMonth($id=null){
		$from = date('Y-m', strtotime('-1 months')).'-01';
		$to = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('affiliatebv');
		$sql = 'SELECT SUM(bv) FROM '.$bvTable.'
		WHERE customer_id = '.$id. ' AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();	
		
		if(isset($data[0]['SUM(bv)']))
			return $data[0]['SUM(bv)'];
		return 0;
	}
	
    /****TEST***/
    public function delete_testAction()
    {
    	 
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		
		
		
		$sql = 'TRUNCATE TABLE usez_company_pool;
				TRUNCATE TABLE usez_company_distribute;
				TRUNCATE TABLE usez_orphan_pool;
				TRUNCATE TABLE usez_orphan_distribute;
				TRUNCATE TABLE usez_groupsales;
				TRUNCATE TABLE usez_transactions; 
				TRUNCATE TABLE usez_commissions;';	
		//$select = $read->query($sql);  
		$sql .= ' SELECT customer_id ,after_groupsales   FROM usez_groupsales
		WHERE 1 ';  
		$select = $read->query($sql);
		
		
		return;
    }
    public function insertbvAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
    public function saveinsertbvAction()
    {
    	$data = $this->getRequest()->getPost();
    	$bv = $data['bv'];
    	
    	$date = $data['date'];
    	$loop = $data['loop'];
    	$customer_id = $data['customer_id'];
    	echo "Insert Completed: <br />";
    	for($i = 1; $i <= $loop;$i++)
    	{
    		$order_id = $this->getRandomString(9);
	    	$groupbv = Mage::getModel('ambassador/groupbv')->setId(null);
	    	$customer = $this->__getCustomer($customer_id);
	    	$groupbv->setCustomerId($customer->getId());
	    	$groupbv->setCustomerFirstname($customer->getFirstname());
	    	$groupbv->setCustomerLastname($customer->getLastname());
	    	$groupbv->setBv($bv);
	    	$groupbv->setBvParentId($customer->getParentId());
	    	$groupbv->setOrderId($order_id);
	    	$groupbv->setCreateDate($date);
	    	$groupbv->setStatus('available');
	    	$groupbv->save();
	    	echo "Row ".$i." : ";
	    	print_r($groupbv->getData());
	    	echo "<br />";
    	}
    	echo '<a href="'.Mage::getUrl('ambassador/index/insertbv').'"> Continue Insert </a>';
    }
 	protected function __getCustomer($id)
    {
    	$customer = Mage::getModel('customer/customer')->load($id);
    	return $customer;
    }
    public function getRandomString($length=null) {
		$bank = '1234567890';
		$result = '';
		while ( $length > 0 ) {
			$result .= substr ( $bank, rand ( 0, strlen ( $bank ) - 1 ), 1 );
			$length --;
		}
		return $result;
	}	
	
	/****ENDTEST*/
    public function distributeCompanyAction()
    {
    	//$this->totalPercentCompanyPool();
		//Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ambassador')->__('Item was successfully saved'));
    	$this->_redirectReferer();
    }
 	public function distributeOrphanAction()
    {
    	//$this->totalPercentOrphanPool();
    	$this->_redirectReferer();
    }
 	public function distributeCommissionAction()
    {
    	$this->_redirectReferer();
    }
    
    public function testAction()
    {
    	$this->checkCustomerUpgradeAmbassador(1);
    	$this->loadLayout();     
		$this->renderLayout();
    }
	
   //VINH
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
		WHERE groupsales_month = \''.$date.'\' ';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
			
   		foreach($list as $k=>$v){
			
			$total=$v['after_groupsales'];
	   		switch($total){
	    		case($total <= 1000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/below1000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 1001 && $total <= 2000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from1001to2000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 2001 && $total <= 5000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from2001to5000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 5001 && $total <= 10000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from5001to10000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 10001 && $total <= 15000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from10001to15000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 15001 && $total <= 25000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from15001to25000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 25001):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/above25001');
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
	    		$groupgl->setGlExpirationDate(date('Y-m-d', strtotime('+ '.$expi.' days')));
	    		$groupgl->setGl($commission);
	    		$groupgl->setStatus('available');
	    		$groupgl->setNote('Earn from Group Sales EL');
	    		$groupgl->save();
	    	}
		}
   }
    
    /**
     * 	Slide 15
	**/
   /**
    * *****************************************************
    * Ham chia loi nhuan bang tien cho Ambassador, sau khi da cong them company Pool va Walk-in-Pool
    */
    public function earnFromGroupsales(){
    	$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('groupsales');
		$sql = 'SELECT customer_id ,after_groupsales   FROM '.$customerTable.'
		WHERE groupsales_month = \''.$date.'\' ';  
		$select = $read->query($sql);
		$list = $select->fetchAll();
		
		foreach($list as $k=>$v){
			$total=$v['after_groupsales'];
	   		switch($total){
	    		case($total <= 1000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/below1000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 1001 && $total <= 2000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/from1001to2000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 2001 && $total <= 5000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/from2001to5000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 5001 && $total <= 10000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/from5001to10000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 10001 && $total <= 15000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/from10001to15000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 15001 && $total <= 25000):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/from15001to25000');
	    			$commission = $total * $per /100;
	    		break;
	    		
	    		case($total >= 25001):
	    			$per = Mage::getStoreConfig('admin/groupsalecommission/above25001');
	    			$commission = $total * $per /100;
	    		break;
	    	}
	    	
	    	$commission = number_format($commission, 0);
	    	if($commission > 0){
		    	$trans = Mage::getModel('ambassador/transactions')->setId(null);
	    		$trans->setCustomerId($v['customer_id']);
	    		$trans->setTransactionsTime($date);
	    		$trans->setCreatedTime(date('Y-m-d H:i:s'));
	    		$trans->setAmount($commission);
	    		$trans->setStatus('Enable');
	    		$trans->setType('Commission');
	    		$trans->setDescription('Earn from Group Sales');
	    		$trans->save();
	    	}
		}
    }
    
    
   /**
    * *********************************************************
    * Ham cong them BV cho Ambassador, BV phat sinh tu company hay Walk-in-Pool
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
			print_r($list[0]);
			$totalAfterGroupsale = $list[0]['after_groupsales'] + $bv;
			$group = Mage::getModel('groupsales/groupsales')->setId($list[0]['groupsales_id']);
	    	$group->setCustomerId($list[0]['customer_id']);
	    	$group->setAfterGroupsales($totalAfterGroupsale);
	    	$group->setUpdatedTime(date('Y-m-d H:i:s'));
	    	$group->setNotes('Update bv for customer id = '.$list[0]['customer_id']);
	    	$group->save();
		}
     }
    
    /**
     * Ham tinh total Groupsale cua tat ca Ambassador, Senior Ambassador trong thang
     * save vao table groupsales
	**/
    
    public function savetoGroupSale()
    {
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id   FROM '.$customerTable.'
		WHERE user_role = \''."ambassador".'\' OR  user_role = \''."seniorambassador".'\' ';  
		$select = $read->query($sql);
		$listCustomerId = $select->fetchAll();
		$list_ids = array();
		foreach($listCustomerId as $k=>$v)
		{
			$list_ids[] = $v['entity_id'];
		}
		$month = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
    	$totalGroupsale = 0;
		
		$customer_data=Mage::getResourceModel('customer/customer_collection')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname')
				->addAttributeToSelect('nric')
				->addAttributeToSelect('bank_name')
				->addAttributeToSelect('account_code')
				->addAttributeToSelect('bank_code')
				->addAttributeToSelect('branch_code');
      
		$names = array();
		$nrics = array();
		$bankNames = array();
		$bankCodes = array();
		$accountCodes = array();
		$branchCodes = array();
		
		foreach ($customer_data as $data){
			$names[$data->getId()] = $data->getFirstname().' '.$data->getLastname();
			$nrics[$data->getId()] = $data->getNric();
			$bankNames[$data->getId()] = $data->getBankName();
			$bankCodes[$data->getId()] = $data->getBankCode();
			$accountCodes[$data->getId()] = $data->getAccountCode();
			$branchCodes[$data->getId()] = $data->getBranchCode();
		}
    	
		foreach($list_ids as $k=>$v){   
			    
    		$totalGroupsale = $this->groupSaleInMonth($v);
			$personalBV = $this->groupBVInMonthByCustomerId($v);
    		$group = Mage::getModel('groupsales/groupsales')->setId(null);
    		$commissions = Mage::getModel('commissions/commissions')->setId(null);
			$group->setCustomerId($v);
			$commissions->setCustomerId($v);
			if(isset($names[$v]))
			{
				$group->setName($names[$v]);
				$commissions->setName($names[$v]);
			}
			else 
			{
				$group->setName("");
				$commissions->setName("");
			}
			
			if(isset($nrics[$v]))
			{
				$commissions->setNric($nrics[$v]);
			}
			else 
			{
				$commissions->setNric("");
			}
			
			if(isset($bankNames[$v]))
			{
				$commissions->setBankName($bankNames[$v]);
			}
			else 
			{
				$commissions->setBankName("");
			}
			
			if(isset($bankCodes[$v]))
			{
				$commissions->setBankCode($bankCodes[$v]);
			}
			else 
			{
				$commissions->setBankCode("");
			}
			
			if(isset($accountCodes[$v]))
			{
				$commissions->setAccountCode($accountCodes[$v]);
			}
			else 
			{
				$commissions->setAccountCode("");
			}
			
			if(isset($branchCodes[$v]))
			{
				$commissions->setBranchCode($branchCodes[$v]);
			}
			else 
			{
				$commissions->setBranchCode("");
			}
			
    		$group->setGroupsalesMonth($month);
			$commissions->setCommissionTime($month);
    		$group->setBeforeGroupsales($personalBV);
			$group->setGroupsales($totalGroupsale);    		
			$min_bv = Mage::getStoreConfig('admin/usezcommon/bvrebate');
			$total = floatval(0);
			if ($personalBV >= $min_bv){
				$total = floatval($totalGroupsale+$personalBV-$min_bv);
				$group->setAfterGroupsales($total);
			}
			else
				$group->setAfterGroupsales(0);
			
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
			
			$glbonus = 0;
			
	   		switch($total){
	    		case($total <= 1000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/below1000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 1001 && $total <= 4000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from1001to4000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 4001 && $total <= 10000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from4001to10000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 10001 && $total <= 18000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from10001to18000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 18001 && $total <= 28000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from18001to28000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 28001 && $total <= 40000):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/from28001to40000');
	    			$glbonus = $total * $per /100;
	    		break;
	    		
	    		case($total >= 40001):
	    			$per = Mage::getStoreConfig('admin/groupsaleglpoint/above40001');
	    			$glbonus = $total * $per /100;
	    		break;
	    	}
	    	//$commission = number_format($commission, 2);
			$group->setCommission($commission);
			$group->setGlBonus($glbonus);
    		$group->setCreatedTime(date('Y-m-d'));
    		$group->setNotes('Total Groupsales in month of customer id '.$v);
    		$group->save();
			
			$commissions->setAffiliate(0);
			$commissions->setOrphanPool(0);
			$commissions->setCompanyPool(0);
			$commissions->setCommission(0);
			$commissions->setTotalIncome(0);
			$commissions->setRebate(0);
			$commissions->setSubtotal(0);
			$commissions->setCreatedTime(date('Y-m-d'));
			$commissions->save();
    	}
    }
	    
    /**
     * Ham save theo tung thang commisson luu vao transaction cho tat Senior Ambassador
     * Luu commission cho SeniorAmbassador co duoc tu Affiliate Merchant groupsale.
	**/
	
public function saveSeniorAffCommission(){
    	$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
    	$affiliate = Mage::getModel('ambassador/affiliatecommission')->getCollection();
    	$affiliate->getSelect()->where('date = ?',$date);
    	$affiliate_data = $affiliate->getData();
    	print_r($affiliate_data);
    	if(count($affiliate_data)==0)
    	{	
	    	$listSeniorAmbassador = $this->getIdListByRole('seniorambassador');
	    	$commission = 0;
	    	foreach($listSeniorAmbassador as $k=>$v){
	    		$commission = $this->payPercentAffiliateMerchant($v);
	    		if($commission > 0){
		    		$trans = Mage::getModel('ambassador/transactions')->setId(null);
		    		$trans->setCustomerId($v);
		    		$trans->setTransactionsTime($date);
		    		$trans->setCreatedTime(date('Y-m-d H:i:s'));
		    		$trans->setAmount($commission);
		    		$trans->setStatus('Enable');
		    		$trans->setType('Commission');
		    		$trans->setDescription('Earn from Referred Affiliate Merchant Sales');
		    		$trans->save();
	    		}
	    	}
	    	$affiliate_model = Mage::getModel('ambassador/affiliatecommission')->setId(null);
	    	$affiliate_model->setDate($date);
	    	$affiliate_model->setCreatedTime(date('Y-m-d H:i:s'));
	    	$affiliate_model->setNote('Distribute Affiliates Commission:'.$date);
	    	$affiliate_model->save();
    	}
    	
    }
    
    /**
     * Ham tinh commission cho mot Senior Ambassador
	**/
    
    public function payPercentAffiliateMerchant($id=null){
    	$total = $this->groupSaleInMonth($id);
    	$totalAffiliate = $this->totalGroupSaleAffiliate($id);
    	$per = 0;
    	$commssion=0;
    	switch($total){
    		case($total >= 1000 && $total <= 2000):
    			$per = Mage::getStoreConfig('admin/affiliate/from1001to2000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 2001 && $total <= 5000):
    			$per = Mage::getStoreConfig('admin/affiliate/from2001to5000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 5001 && $total <= 10000):
    			$per = Mage::getStoreConfig('admin/affiliate/from5001to10000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 10001 && $total <= 15000):
    			$per = Mage::getStoreConfig('admin/affiliate/from10001to15000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 15001 && $total <= 20000):
    			$per = Mage::getStoreConfig('admin/affiliate/from15001to20000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		case($total >= 20001 && $total <= 25000):
    			$per = Mage::getStoreConfig('admin/affiliate/from20001to25000');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    		
    		case($total >= 25001):
    			$per = Mage::getStoreConfig('admin/affiliate/above25001');
    			$commssion = $totalAffiliate * $per /100;
    		break;
    	}
    	$commssion = number_format($commssion, 0);
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
    
    
    
    /**
		End Tinh transaction cho Senior Ambassador
	**/
    
    
    
    
    /** Company Distribute **/
 	public function totalPercentCompanyPool(){
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSale = $this->totalGroupSaleBvCompanyPool();
		$totalCompanyPool = $this->getArrayCompanyPoolGroupByMonth();
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		foreach($listCustomerId as $k=>$v){
			if($this->checkMinGroupSaleBVCompanyPool($v) == true){
				$groupSale = $this->groupSaleInMonthFinal($v);
				if($totalGroupSale != 0)
					$percent = $groupSale / $totalGroupSale;
				else 
					$percent = 0;
				$payCompanyBV = number_format($percent * $totalCompanyPool,0);
				
				if($payCompanyBV > 0){
					$companyDistribute = Mage::getModel('ambassador/companyDistribute')->setId(null);
					$companyDistribute->setCustomerId($v['entity_id']);
					$companyDistribute->setDate(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
					$companyDistribute->setCreatedAt(date('Y-m-d'));
					$companyDistribute->setCompanyBv($payCompanyBV);
					$companyDistribute->save();
					
					
					$this->updatetoGroupSale($v,$date,$payCompanyBV);
				}
			} 	
		}
    }
public function getEditCompanyPool($date)
    {
    	$company_pool = Mage::getModel('companypool/companypool')->getCollection();
    	$company_pool->getSelect()->where('company_time = ?',$date)->where('status <> \'distributed\'');
    	$company_pool_data = $company_pool->getData();
    	print_r($company_pool_data);
    	if(isset($company_pool_data[0]['company_edit_amount']))
    	{
    		return $company_pool_data[0]['company_edit_amount'];
    	}
    	else
    	{
    		return 0;
    	}
    }
    
  	public function totalGroupSaleBvCompanyPool(){
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSaleBV=0;
		$tmp = 0;
		$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		foreach($listCustomerId as $k=>$v){
			if($this->checkMinGroupSaleBVCompanyPool($v) == true){
				$tmp = $this->groupSaleInMonthFinal($v);
				$totalGroupSaleBV += $tmp;
			}
		}
		
		return $totalGroupSaleBV;
    }
    
    public function checkMinGroupSaleBVCompanyPool($id=null){
		$groupSales = $this->groupSaleInMonthFinal($id);
		$mingroupsaleCompanyPool = Mage::getStoreConfig('admin/usezcommon/bvcompanypool');
		if(isset($groupSales) && $groupSales >= $mingroupsaleCompanyPool)
			return true;
		return false;
    }
    
    /** End Company Distribute **/
    
    
    /** Orphan Distribute **/
    
    public function totalPercentOrphanPool(){
		$listCustomerId = $this->getIdListByRole('seniorambassador');
		$totalGroupSale = $this->totalGroupSaleBv();
		$totalOprhanPool = $this->getTotalOrphanPool();
		$date = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		foreach($listCustomerId as $k=>$v){
			if($this->checkMinGroupSaleBVOrphanPool($v) == true){
				$groupSale = $this->groupSaleInMonthFinal($v);
				if($totalGroupSale != 0)
					$percent = $groupSale / $totalGroupSale;
				else 
					$percent = 0;
				$payOrphanBV = number_format($percent * $totalOprhanPool,0);
				
				if($payOrphanBV > 0){
					$orphanDistribute = Mage::getModel('ambassador/orphanDistribute')->setId(null);
					$orphanDistribute->setCustomerId($v);
					$orphanDistribute->setDate(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
					$orphanDistribute->setCreatedAt(date('Y-m-d'));
					$orphanDistribute->setOrphanBv($payOrphanBV);
					$orphanDistribute->save();
					
					$this->updatetoGroupSale($v,$date,$payOrphanBV);
				}
			} 	
		}
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
		
	public function totalGroupSaleBvById($id=null){
		$groupSales = $this->groupSaleInMonth($id);
		$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		
		if(isset($groupSales) && $groupSales >= $mingroupsale)
			return $groupSales;
		return 0;
	} 
    
	public function checkMinGroupSaleBVOrphanPool($id=null){
		$groupSales = $this->groupSaleInMonthFinal($id);
		$mingroupsaleorphanPool = Mage::getStoreConfig('admin/usezcommon/bvorphanpool');
		
		if(isset($groupSales) && $groupSales >= $mingroupsaleorphanPool)
			return true;
		return false;
		
	}
    
    /** End Orphan Distribute **/
	
    /** Total Company Pool on month
     *  Save(); 
	**/
 	public function totalCompanyPoolGroupByMonth(){
		$companyPool = Mage::getModel('ambassador/companyPool')->setId(null);
		$companyPool->setCompanyTime(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
		$total = $this->getArrayCompanyPoolGroupByMonth();
		$check_time = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$company_collection = Mage::getModel('companypool/companypool')->getCollection();
		$company_collection->getSelect()->where('company_time = ?',$check_time);
		$company_data = $company_collection->getData();
		
		if(count($company_data) > 0)
		{
			echo count($company_data);
		}
		else 
		{	
			$companyPool->setCompanyRealAmount($total);
			$companyPool->setCompanyEditAmount($total);
			$companyPool->setNotes('Company Pool date :'.date('Y-m'));
			$companyPool->save();
		}
    }
    
    /**
     * Ham tra ve total Company Pool on month cua tat ca cac Ambassador khong duoc active
	**/
    public function getArrayCompanyPoolGroupByMonth(){
		$listCustomerId = $this->getIdListNotByRole('customer');
		$totalCompanyPool = 0;
		foreach($listCustomerId as $k=>$v){
			if($this->checkActive($v)== false){
				$totalCompanyPool += $this->groupSaleInMonth($v);
			}
		}
		return $totalCompanyPool;
    }
    
	public function getIdListNotByRole($role='')
	{
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$customerTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id   FROM '.$customerTable.'
		WHERE user_role <> \''.$role.'\' ';  
		$select = $read->query($sql);
		$listCustomerId = $select->fetchAll();
		$list_ids = array();
		foreach($listCustomerId as $k=>$v)
		{
			$list_ids[] = $v['entity_id'];
		}
		return $list_ids;
	}
	
	
    /**
     * Ham tinh total Company Pool cua 1 parent_id
	**/
  /*public function getTotalCompanyPool($parent_id=null){
		$groupSales = $this->groupSaleInMonth($parent_id);
		if(isset($groupSales))
			return $groupSales;
		return 0;
    }*/
    
    
    
    /** Total Walk-in-Pool on month 
     *  Save() 
 	**/
    public function totalOrphanPoolGroupByMonth(){
		$orphanPool = Mage::getModel('ambassador/orphanPool')->setId(null);
		$orphanPool->setOrphanTime(date('Y-m-d', strtotime(date('Y-m').'-01 -1 days')));
		$total = $this->getTotalOrphanPool();
		$check_time = date('Y-m-d', strtotime(date('Y-m').'-01 -1 days'));
		$orphan_collection = Mage::getModel('orphanpool/orphanpool')->getCollection();
		$orphan_collection->getSelect()->where('orphan_time = ?',$check_time);
		$orphan_data = $orphan_collection->getData();
		
		if(count($orphan_data) > 0)
		{
			echo count($orphan_data);
		}
		else 
		{
			$orphanPool->setOrphanRealAmount($total);
			$orphanPool->setOrphanEditAmount($total);
			$orphanPool->setNotes('Walk-in-Pool date :'.date('Y-m'));
			$orphanPool->save();
		}
		
    }
    
    /**
     * Ham tinh total Walk-in-Pool
     * DK: parent_id = 0 
	**/
    
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
    
    
    /**
     *  function send mail when Ambassador qualified Senior Ambassador
     *  
	**/
    public function cronAction()
    {
    	/*$listCustomerId = $this->getIdListByRole('ambassador');
    	foreach($listCustomerId as $k=>$v)
    		$this->checkSeniorAmbassador($v);*/
    }
    public function checkAllAmbassadorToSenior()
    {
    	$list_ids = $this->getIdListByRole('ambassador');
    	foreach ($list_ids as $id)
    	{
    		$this->checkSeniorAmbassador($id);
    	}
    	
    }
    /**
     * Ham kiem tra 1 customer co du dieu kien tro thanh Senior Ambassador chua?
     * DK1: 5 customer cap con duoc active
     * DK2: Purchase personal > 500
     * DK3: Group sales > 5000
	**/
    public function checkSeniorAmbassador($id=null){
    	$groupbv = Mage::getModel('ambassador/groupbv')->getCollection();
    	$groupbv->getSelect()->where('customer_id = ?',$id);
		$groupbv_data = $groupbv->getData();
   		$fullname='';
		if(isset($groupbv_data) && $groupbv_data != null){
			$fullname = $groupbv_data[0]['customer_firstname'].' '.$groupbv_data[0]['customer_lastname'];
		}
		
		$customer = Mage::getModel('customer/customer')->getCollection();
		$customer->getSelect()->where('entity_id = ?',$id);
		$customer_data = $customer->getData();
		$customer_data=$customer_data[0];
		if($this->check5Active($id)== true && $this->checkPurchaseBV($id)== true && $this->checkGroupSaleBV($id)== true){
			$customer_model = Mage::getModel('customer/customer')->load($id);
			$customer_model->setUserRole('seniorambassador');
			$customer_model->setUpgradation(date("jS F, Y"));
			$customer_model->save();
			
			//echo $id;
			$admin_email = Mage::getModel('admin/user')->load(2)->getEmail();
			$general_mail = Mage::getConfig()->getNode('default/trans_email/ident_general/email')->asArray();								
			$general_name = Mage::getConfig()->getNode('default/trans_email/ident_general/name')->asArray();
		
			$_sendTo_email = $customer_data['email'];
			$_sendFrom_email = $general_mail;
			 
			
			$mail = new Zend_Mail();
			$mail->addTo($_sendTo_email, $fullname);
			$mail->setFrom($general_mail, $general_name);
			$mail->setSubject('Become Ambassador');
		
			$emailTemplate  = Mage::getModel('core/email_template')->loadDefault('alert_become_senior_ambassador');
		
			$emailVari = array();
			$emailVari['myvar'] = $fullname;
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailVari);
			print_r($processedTemplate);
			$mail->setBodyHtml($processedTemplate);
			try {
				$mail->send();
				}
				catch (Exception $ex) {
			}
		}
	}
	
	/**
	 * Ham kiem tra DK tro thanh Senior Ambassador
	 * DK: total Group sale > 5000
	 * Group sales la total bv customer cap con cua no
	**/
	public function checkGroupSaleBV($id=null){
		$groupSale = $this->groupSaleInMonthSA($id);
		$mingroupsale = Mage::getStoreConfig('admin/ambassador/mingroupsale');
		
		if(isset($groupSale) && $groupSale >= $mingroupsale)
			return true;
		return false;
		
	}


	
	/**
	 * Ham kiem tra DK tro thanh Ambassador 
	 * DK: Purchase personal > 500
	**/
	public function checkPurchaseBV($id=null){
		$total = $this->groupBVInAnyMonthByCustomerId($id);
		$upgradebv = Mage::getStoreConfig('admin/ambassador/upgradebv');
		
		if(isset($total) && $total >= $upgradebv)
			return true;
		return false;
	
	}

	
	
	/**
		Ham de kiem tra DK xem 1 Ambassador co du 5 distributor duoc active hay khong?
	**/
	public function check5Active($id=null){
		$resource = Mage::getSingleton('core/resource');
	    $read= $resource->getConnection('core_read');
	    $customerTable = $resource->getTableName('customer_entity');
		$selCustomer = $read->select()
		    ->from($customerTable,array('*'))
		    ->where('parent_id=?', $id)
			->where('user_role <> \'customer\'');
		$row = $read->fetchAll($selCustomer);
		$i=0;
		if(isset($row) && $row != null){
			
			foreach($row as $k=>$v){				
				$i++;				
			}
		}
		
		$minchild = Mage::getStoreConfig('admin/ambassador/minchild');

		if($i >= $minchild)
		{
			return true;
		}
		return false;
	}
	
	/**
		Ham dung de kiem tra xem 1 customer co active hay khong?
		DK: total bv > 50
	**/
	
	public function checkActive($id=null){
		$total = $this->groupBVInMonthByCustomerId($id);
	
		$bvrebate = Mage::getStoreConfig('admin/usezcommon/bvrebate');
		if(isset($total) && $total >= $bvrebate)
			return true;
		return false;
	}

	public function checkActiveSA($id=null){
		$total = $this->groupBVInMonthByCustomerId($id);
		$bvrebate = Mage::getStoreConfig('admin/usezcommon/bvrebate');
		if(isset($total) && $total >= $bvrebate)
			return true;
		return false;
	}

	//check tat ca customer duoc len Ambassador
	
	public function checkAllCustomerToAmbassador()
	{
		$list_ids = $this->getIdListByRole('customer');
		foreach ($list_ids as $id)
		{
			$this->checkAmbassador($id);
		}
	}
	/**
		Ham kiem tra  tu VIP Customer len Ambassador
	**/
	
 	public function checkAmbassador($id=null){
    	$groupbv = Mage::getModel('ambassador/groupbv')->getCollection();
    	$groupbv->getSelect()->where('customer_id = ?',$id);
		$groupbv_data = $groupbv->getData();
   		$fullname='';
		if(isset($groupbv_data) && $groupbv_data != null){
			$fullname = $groupbv_data[0]['customer_firstname'].' '.$groupbv_data[0]['customer_lastname'];
		}
		
		$customer = Mage::getModel('customer/customer')->getCollection();
		$customer->getSelect()->where('entity_id = ?',$id);
		$customer_data = $customer->getData();
		$customer_data=$customer_data[0];
		if($this->checkCustomerUpgradeAmbassador($id)== true){
			$admin_email = Mage::getModel('admin/user')->load(2)->getEmail();
			$general_mail = Mage::getConfig()->getNode('default/trans_email/ident_general/email')->asArray();								
			$general_name = Mage::getConfig()->getNode('default/trans_email/ident_general/name')->asArray();
		
			$_sendTo_email = $customer_data['email'];
			$_sendFrom_email = $general_mail;
			 
			
			$mail = new Zend_Mail();
			$mail->addTo($_sendTo_email, $fullname);
			$mail->setFrom($general_mail, $general_name);
			$mail->setSubject('Become Distributor');
		
			$emailTemplate  = Mage::getModel('core/email_template')->loadDefault('alert_become_ambassador');
		
			$emailVari = array();
			$emailVari['myvar'] = $fullname;
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailVari);
			//print_r($processedTemplate);
			$mail->setBodyHtml($processedTemplate);
			try {
				
				$mail->send();
				$customer_model = Mage::getModel('customer/customer')->load($id);
				$customer_model->setSendMail('yes');
				$customer_model->save();
				}
				catch (Exception $ex) {
			}
		}
	}
	
	public function checkCustomerUpgradeAmbassador($id=null){
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT customer_id  , SUM(bv) FROM '.$bvTable.'
		WHERE customer_id = '.$id. ' 
		GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();	
		$bvrequire = Mage::getStoreConfig('admin/vipcustomer/bvrequire');
		$customer = Mage::getModel('customer/customer')->load($id);
		if($customer->getSendMail() == 'no')
		{
			if(isset($data[0]['SUM(bv)']) && $data[0]['SUM(bv)'] >= $bvrequire)
				return true;
		}
		return false;
	}
	
	/**
	 * End 
	**/
	
	
	
	/**          Ham co ban                  **/
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

	public function groupSaleInMonthSA($id=null){
		$from = date('Y-m').'-01';
		$to = date('Y-m-d');
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT bv_parent_id  , SUM(bv) FROM '.$bvTable.'
		WHERE bv_parent_id = '.$id. ' AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY bv_parent_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();	
		$groupSales = 0;
		if(isset($data[0]['SUM(bv)']))
			$groupSales += $data[0]['SUM(bv)'];
		else
			$groupSales += 0;
		
		$from = date('Y-m').'-01';
		$to = date('Y-m-d');
		
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT customer_id  , SUM(bv) FROM '.$bvTable.'
		WHERE customer_id = '.$id. ' AND create_date >= \''.$from.'\' AND create_date <= \''.$to.'\' 
		GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		$personalBV = 0;
		if(isset($data[0]['SUM(bv)']))
			$personalBV += $data[0]['SUM(bv)'];
		else
			$personalBV += 0;
		
		$min_bv = Mage::getStoreConfig('admin/ambassador/upgradebv');
		return $groupSales+$personalBV-$min_bv;
		
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

	public function groupBVInAnyMonthByCustomerId($id=null){
		$from = date('Y-m').'-01';
		$to = date('Y-m-d');
		
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
	
	public function getExAffiliatesAction()
	{
		//echo "kdhfkdfdhf";
		$today = date('Y-m-d');
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('customer_entity');
		$sql = 'SELECT entity_id FROM '.$bvTable.'
		WHERE expiration_date < \''.$today.'\' AND is_affiliate = \'yes\' ';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		$aff_ids = '(';
		foreach ($data as $entity)
		{
			$aff_ids .= $entity['entity_id'].',';
			$customer = Mage::getModel('customer/customer')->load($entity['entity_id']);
			$customer->setData('is_affiliate','no');
			$customer->save();
		}
		$aff_ids .= '0)';
		
		$bvTable = $resource->getTableName('admin_user');
		$sql = 'SELECT user_id FROM '.$bvTable.'
		WHERE customer_id IN '.$aff_ids;
		$select = $read->query($sql);
		$data = $select->fetchAll();
		
		$admin_ids = array();
		foreach ($data as $entity)
		{
			$admin_ids[] = $entity['user_id'];
		}
		
		
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection = $collection->addFieldToFilter('seller_id',array('or'=>$admin_ids));
		$result = $collection->getData();
		$storeId    = (int)$this->getRequest()->getParam('store', 0);
       	$status     = 2;
       	$statusModel = Mage::getModel('catalog/product_status');
		foreach ($result as $k=>$v)
		{
        	$statusModel->updateProductStatus($v['entity_id'], $storeId, $status);
		}
		
	}
	
}