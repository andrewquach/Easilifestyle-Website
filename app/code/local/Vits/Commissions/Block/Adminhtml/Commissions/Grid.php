<?php

class Vits_Commissions_Block_Adminhtml_Commissions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('commissionsGrid');
      $this->setDefaultSort('commissions_id');
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
  	 $collection = Mage::getModel('commissions/commissions')->getCollection();
	 $collection->getSelect()->where('subtotal > 0');
      $this->setCollection($collection);
      parent::_prepareCollection();

        return $this;
	 
      
  }

  protected function _prepareColumns()
  {
      $this->addColumn('commissions_id', array(
          'header'    => Mage::helper('commissions')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'commissions_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('commissions')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));
        
	$this->addColumn('nric', array(
          'header'    => Mage::helper('commissions')->__('NRIC/FIN'),
          'align'     =>'left',
          'index'     => 'nric',
      ));
	
	$this->addColumn('account_code', array(
          'header'    => Mage::helper('commissions')->__('Bank Account Number'),
          'align'     =>'left',
          'index'     => 'account_code',
      ));
	  
	  $this->addColumn('bank_name', array(
          'header'    => Mage::helper('commissions')->__('Bank Name'),
          'align'     =>'left',
          'index'     => 'bank_name',
      ));
		$this->addColumn('bank_code', array(
          'header'    => Mage::helper('commissions')->__('Bank No'),
          'align'     =>'left',
          'index'     => 'bank_code',
      ));
		
		$this->addColumn('branch_code', array(
          'header'    => Mage::helper('commissions')->__('Branch Code'),
          'align'     =>'left',
          'index'     => 'branch_code',
      ));
		
       $this->addColumn('commission_time', array(
          'header'    => Mage::helper('commissions')->__('Month'),
          'align'     =>'left',
          'index'     => 'commission_time',
      ));
	  
       $store = $this->_getStore();
       
	   $this->addColumn('affiliate', array(
          'header'    => Mage::helper('commissions')->__('Affiliate'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'affiliate',
      ));
      
	  
	  $this->addColumn('orphan_pool', array(
          'header'    => Mage::helper('commissions')->__('Walk-In Pool'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'orphan_pool',
      ));
	  
	  $this->addColumn('company_pool', array(
          'header'    => Mage::helper('commissions')->__('Company Pool'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'company_pool',
      ));
	  
	  $this->addColumn('commission', array(
          'header'    => Mage::helper('commissions')->__('Commision'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'commission',
      ));
	  
	  $this->addColumn('total_income', array(
          'header'    => Mage::helper('commissions')->__('Total Income'),
         'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'total_income',
      ));
	  
       $this->addColumn('rebate', array(
          'header'    => Mage::helper('commissions')->__('Rebate'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'rebate',
      ));
      
        $this->addColumn('subtotal', array(
          'header'    => Mage::helper('commissions')->__('Sub Total'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'subtotal',
      ));
      
        


	  
        // $this->addColumn('action',
            // array(
                // 'header'    =>  Mage::helper('commissions')->__('Action'),
                // 'width'     => '100',
                // 'type'      => 'action',
                // 'getter'    => 'getId',
                // 'actions'   => array(
                    // array(
                        // 'caption'   => Mage::helper('commissions')->__('Edit'),
                        // 'url'       => array('base'=> '*/*/edit'),
                        // 'field'     => 'id'
                    // )
                // ),
                // 'filter'    => false,
                // 'sortable'  => false,
                // 'index'     => 'stores',
                // 'is_system' => true,
        // ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('commissions')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('commissions')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('commissions_id');
        $this->getMassactionBlock()->setFormFieldName('commissions');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('commissions')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('commissions')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('commissions/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('commissions')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('commissions')->__('Status'),
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