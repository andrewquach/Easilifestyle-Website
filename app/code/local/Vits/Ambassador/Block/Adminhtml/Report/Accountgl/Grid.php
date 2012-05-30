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
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Report Sold Products Grid Block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Vits_Ambassador_Block_Adminhtml_Report_Accountgl_Grid extends Vits_Ambassador_Block_Adminhtml_Report_Grid
{
    /**
     * Sub report size
     *
     * @var int
     */
    protected $_subReportSize = 0;

    /**
     * Initialize Grid settings
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridProductsSold');
    }

    /**
     * Prepare collection object for grid
     *
     * @return Vits_Ambassador_Block_Adminhtml_Report_Product_Sold_Grid
     */
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()
            ->initReport('reports/accountgl_collection');
        return $this;
    }

    /**
     * Prepare Grid columns
     *
     * @return Vits_Ambassador_Block_Adminhtml_Report_Product_Sold_Grid
     */
    protected function _prepareColumns()
    {
       /* $this->addColumn('customer_id', array(
            'header'    =>Mage::helper('reports')->__('Customer ID'),
            'index'     =>'customer_id'
        ));*/
        /*$this->addColumn('customer_firstname', array(
            'header'    =>Mage::helper('reports')->__('Customer Firstname'),
            'index'     =>'customer_firstname'
        ));
        $this->addColumn('customer_lastname', array(
            'header'    =>Mage::helper('reports')->__('Customer Lastname'),
            'index'     =>'customer_lastname'
        ));*/
         $this->addColumn('create_date', array(
            'header'    =>Mage::helper('reports')->__('Create Date'),
            'index'     =>'create_date',
         	'type'		=>'date',
        ));
         $this->addColumn('gl_expiration_date', array(
            'header'    =>Mage::helper('reports')->__('Expiration Date'),
            'index'     =>'gl_expiration_date',
            'type'		=>'date',
        ));
       

        $this->addColumn('gl', array(
            'header'    =>Mage::helper('reports')->__('EL Earn'),
            'width'     =>'120px',
            'align'     =>'right',
            'index'     =>'gl',
            'total'     =>'sum',
            'type'      =>'number'
        ));
        $this->addColumn('order_id', array(
            'header'    =>Mage::helper('reports')->__('Earn Bonus'),
            'width'     =>'120px',
            'align'     =>'right',
            'index'     =>'order_id'            
        ));
		
        $this->addColumn('gl_used', array(
            'header'    =>Mage::helper('reports')->__('EL Spent'),
            'width'     =>'120px',
            'align'     =>'right',
            'index'     =>'gl_used',
            'total'     =>'sum',
            'type'      =>'number'
        ));
        $this->addColumn('gl_have', array(
            'header'    =>Mage::helper('reports')->__('EL Balance'),
            'width'     =>'120px',
            'align'     =>'right',
            'index'     =>'gl_have',
            'total'     =>'sum',
            'type'      =>'number'
        ));
         /*$this->addColumn('user_role', array(
            'header'    =>Mage::helper('reports')->__('User Role'),
            'index'     =>'user_role'
        ));*/
    	$customer_id = $this->getRequest()->getParam('id');
        if($customer_id != null)
    	{
    		 $this->setExportVisibility(false);
    	}
        $this->addExportType('*/*/exportAccountglCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportAccountglExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }
}
