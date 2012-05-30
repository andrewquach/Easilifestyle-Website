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
 * Adminhtml customer grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Vits_Ambassador_Block_Adminhtml_Groupbv_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

public function __construct()
  {
      parent::__construct();
      $this->setId('groupbvGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ambassador/groupbv')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
          'header'    => Mage::helper('ambassador')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('customer_id', array(
          'header'    => Mage::helper('ambassador')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
      ));
      $this->addColumn('order_id', array(
          'header'    => Mage::helper('ambassador')->__('Order'),
          'align'     =>'left',
          'index'     => 'order_id',
      ));
	$this->addColumn('create_date', array(
          'header'    => Mage::helper('ambassador')->__('Create Date'),
          'align'     =>'left',
          'index'     => 'create_date',
      ));
      $this->addColumn('expiration_date', array(
          'header'    => Mage::helper('ambassador')->__('Expiration Date'),
          'align'     =>'left',
          'index'     => 'expiration_date',
      ));
      $this->addColumn('bv', array(
          'header'    => Mage::helper('ambassador')->__('BV'),
          'align'     =>'left',
          'index'     => 'bv',
      ));
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('ambassador')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('ambassador')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('ambassador')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('ambassador')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('ambassador')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('ambassador')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('ambassador_id');
        $this->getMassactionBlock()->setFormFieldName('ambassador');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('ambassador')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('ambassador')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('ambassador/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('ambassador')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('ambassador')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}
