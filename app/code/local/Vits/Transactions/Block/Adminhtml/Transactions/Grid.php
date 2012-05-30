<?php

class Vits_Transactions_Block_Adminhtml_Transactions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('transactionsGrid');
      $this->setDefaultSort('transactions_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }
 protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
  protected function _prepareCollection()
  {
	  $collection = Mage::getModel('transactions/transactions')->getCollection();
      $this->setCollection($collection);
      parent::_prepareCollection();
       $this->getCollection()
       ->addCustomerName();
        return $this;
  }

  protected function _prepareColumns()
  {
       $this->addColumn('transactions_id', array(
          'header'    => Mage::helper('transactions')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'transactions_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('transactions')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));
       $this->addColumn('remark', array(
          'header'    => Mage::helper('transactions')->__('Remark'),
          'align'     =>'left',
          'index'     => 'remark',
      ));
      
       $this->addColumn('transactions_time', array(
          'header'    => Mage::helper('transactions')->__('Transaction Time'),
          'align'     =>'left',
          'index'     => 'transactions_time',
       		'type'	  =>'date',
      ));
      	 $store = $this->_getStore();
       $this->addColumn('amount', array(
          'header'    => Mage::helper('transactions')->__('Amount'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'amount',
      ));
      
       $this->addColumn('status', array(
          'header'    => Mage::helper('transactions')->__('Status'),
          'align'     =>'left',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              'enable' => 'Enable',
              'disable' => 'Disable',	
        	  
        	  )
      ));
      
        $this->addColumn('type', array(
          'header'    => Mage::helper('transactions')->__('Type'),
          'align'     =>'left',
          'index'     => 'type',
          'type'      => 'options',
          'options'   => array(
              'Commission' => 'Commission',
        	  'Rebate'	=>'Cash Rebate',
              'sale' => 'Sale Product',	
        	  'payment'=>'Admin Payment',
			  'Walk-in Pool Distribution'=>'Walk-in Pool Distribution',
			  'Company Pool Distribution'=>'Company Pool Distribution',
			  'Affiliate Commission'=>'Affiliate Commission'
        	  )
      ));


	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('transactions')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('transactions')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('transactions')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('transactions')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('transactions_id');
        $this->getMassactionBlock()->setFormFieldName('transactions');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('transactions')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('transactions')->__('Are you sure to delete selected Transaction(s)?')
        ));

        $statuses = Mage::getSingleton('transactions/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('transactions')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('transactions')->__('Status'),
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