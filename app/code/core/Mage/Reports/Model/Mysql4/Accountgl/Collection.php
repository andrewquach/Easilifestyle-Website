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
 * @package    Mage_Reports
 * @copyright  Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Report Sold Products collection
 *
 * @category   Mage
 * @package    Mage_Reports
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Reports_Model_Mysql4_Accountgl_Collection extends Vits_Ambassador_Model_Mysql4_Groupgl_Collection 
{
    /**
     * Set Date range to collection
     *
     * @param int $from
     * @param int $to
     * @return Mage_Reports_Model_Mysql4_Product_Sold_Collection
     */
    public function setDateRange($from, $to)
    {
       $this->_reset()
            ->addOrderedQty($from, $to);
        return $this;
    }

    /**
     * Set Store filter to collection
     *
     * @param array $storeIds
     * @return Mage_Reports_Model_Mysql4_Product_Sold_Collection
     */
    public function setStoreIds($storeIds)
    {
       /* $storeId = array_pop($storeIds);
        $this->setStoreId($storeId);
        $this->addStoreFilter($storeId);*/
        return $this;
    }
 	public function addOrderedQty($from = '', $to = '')
    {
    
    	$condition = '';
        if ($from != '' && $to != '') {
            $dateFilter = " create_date BETWEEN '{$from}' AND '{$to}'";
        } else {
            $dateFilter = "";
        }
        $condition .= $dateFilter;
        $today = date('Y-m-d');
        $expiration = ' AND gl_expiration_date >= \''.$today.'\' ';
        $condition .= $expiration;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
    	$session_data = $customer->getData();
        if(isset($session_data['child_gl_report']))
        {
        	$condition .= ' AND customer_id = '.$session_data['child_gl_report'].' ';
        }
        else 
        {
	        if($customer instanceof Mage_Customer_Model_Customer)
	        {
	        	$data = $customer->getData();
	        	if(isset($data['user_role']))
	        	{
	        		$condition .= ' AND customer_id = '.$data['entity_id'].' ';
	        	}
	        }
        }
        $expr = "gl -IFNULL(gl_used,0)";
            $this->getSelect()->joinRight(array('e'=>'usez_customer_entity'),'e.entity_id = customer_id')
            	->where($condition);
             $this->getSelect()
	            ->from('', array("gl_have" => "{$expr}"));
        return $this;
    }
}
