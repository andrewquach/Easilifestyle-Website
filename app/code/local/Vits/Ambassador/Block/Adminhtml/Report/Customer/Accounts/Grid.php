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
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml new accounts report grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Vits_Ambassador_Block_Adminhtml_Report_Customer_Accounts_Grid extends Vits_Ambassador_Block_Adminhtml_Report_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('gridAccounts');
    }

    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()->initReport('reports/accounts_collection');
    }

    protected function _prepareColumns()
    {
        $this->addColumn('accounts', array(
            'header'    =>Mage::helper('reports')->__('Number of New Accounts'),
            'index'     =>'accounts',
            'total'     =>'sum',
            'type'      =>'number'
        ));

        $this->addExportType('*/*/exportAccountsCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportAccountsExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }
}