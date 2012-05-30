<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Customer
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customers collection
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Customer_Model_Entity_Customer_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('customer/customer');
    }

    public function groupByEmail()
    {
        $this->getSelect()
            ->from(array('email'=>$this->getEntity()->getEntityTable()),
                array('email_count'=>new Zend_Db_Expr('COUNT(email.entity_id)'))
            )
            ->where('email.entity_id=e.entity_id')
            ->group('email.email');
        return $this;
    }

    public function addNameToSelect()
    {
        $fields = array();
        foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
            if ($node->is('name')) {
                //$this->addAttributeToSelect($code);
                $fields[$code] = $code;
            }
        }

        $expr = 'CONCAT('
            .(isset($fields['prefix']) ? 'IF({{prefix}} IS NOT NULL AND {{prefix}} != "", CONCAT({{prefix}}," "), ""),' : '')
            .'{{firstname}}'.(isset($fields['middlename']) ?  ',IF({{middlename}} IS NOT NULL AND {{middlename}} != "", CONCAT(" ",{{middlename}}), "")' : '').'," ",{{lastname}}'
            .(isset($fields['suffix']) ? ',IF({{suffix}} IS NOT NULL AND {{suffix}} != "", CONCAT(" ",{{suffix}}), "")' : '')
        .')';

        $this->addExpressionAttributeToSelect('name', $expr, $fields);
        return $this;
    }

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);

        $countSelect->from('', 'COUNT(*)');
        $countSelect->resetJoinLeft();

        return $countSelect;
    }

    /**
     * Retrive all ids for collection
     *
     * @return array
     */
    public function getAllIds($limit=null, $offset=null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->from(null, 'e.'.$this->getEntity()->getIdFieldName());
        $idsSelect->limit($limit, $offset);
        $idsSelect->resetJoinLeft();

        return $this->getConnection()->fetchCol($idsSelect, $this->_bindParams);
    }
    
    /**
     * *******************************
     * USEZ function
     * 
     */
    public function addBvToResult()
    {
    	$bv_array = array();
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$bvTable = $resource->getTableName('groupbv');
		$sql = 'SELECT customer_id  , SUM(bv) FROM '.$bvTable.'
				 GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		foreach ($data as $k=>$v)
		{
			$bv_array[$v['customer_id']] = $v['SUM(bv)'];
		}
    	foreach ($this as $customer)
    	{
    		if(isset($bv_array[$customer->getId()]))
    		{
    			$customer->setData('total_bv',$bv_array[$customer->getId()]);
    		}
    		else 
    		{
    			$customer->setData('total_bv',0);
    		}
    	}
    	return $this;
    }
	public function addGlToResult()
    {
    	$today = date('Y-m-d');
    	$gl_array = array();
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$glTable = $resource->getTableName('groupgl');
		$sql = 'SELECT customer_id  , SUM(gl), SUM(gl_used) FROM '.$glTable.'
				WHERE gl_expiration_date >= \''.$today.'\'
				 GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		foreach ($data as $k=>$v)
		{
			$gl_array[$v['customer_id']] = $v['SUM(gl)'] - $v['SUM(gl_used)'];
		}
    	foreach ($this as $customer)
    	{
    		if(isset($gl_array[$customer->getId()]))
    		{
    			$customer->setData('total_gl',$gl_array[$customer->getId()]);
    		}
    		else 
    		{
    			$customer->setData('total_gl',0);
    		}
    	}
    	return $this;
    }
	public function addBalanceToResult()
    {
    	$today = date('Y-m-d');
    	$transactions_array = array();
    	$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$transactionsTable = $resource->getTableName('transactions');
		$sql = 'SELECT customer_id  , SUM(amount) FROM '.$transactionsTable.'
				GROUP BY customer_id';
		$select = $read->query($sql);
		$data = $select->fetchAll();
		foreach ($data as $k=>$v)
		{
			$transactions_array[$v['customer_id']] = $v['SUM(amount)'];
		}
    	foreach ($this as $customer)
    	{
    		if(isset($transactions_array[$customer->getId()]))
    		{
    			$customer->setData('balance',$transactions_array[$customer->getId()]);
    		}
    		else 
    		{
    			$customer->setData('balance',0);
    		}
    	}
    	return $this;
    }
}