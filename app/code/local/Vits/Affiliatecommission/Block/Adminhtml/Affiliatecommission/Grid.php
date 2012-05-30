<?php

class Vits_Affiliatecommission_Block_Adminhtml_Affiliatecommission_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
      $this->setId('affiliatecommissionGrid');
      $this->setDefaultSort('affiliatecommission_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('affiliatecommission/affiliatecommission')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  
  protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

  protected function _prepareColumns()
  {
      $this->addColumn('affiliatecommission_id', array(
          'header'    => Mage::helper('affiliatecommission')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'affiliatecommission_id',
      ));

	  $this->addColumn('name', array(
          'header'    => Mage::helper('affiliatecommission')->__('Affiliator'),
          'align'     =>'left',
          'index'     => 'name',
      ));
	  
      $this->addColumn('affiliate_time', array(
          'header'    => Mage::helper('affiliatecommission')->__('Time'),
          'align'     =>'left',
          'index'     => 'affiliate_time',
      ));
      
      $this->addColumn('affiliate_bv', array(
          'header'    => Mage::helper('affiliatecommission')->__('Total Affiliate BV'),
          'align'     =>'left',
          'index'     => 'affiliate_bv',
      ));
      
      $this->addColumn('groupsales', array(
          'header'    => Mage::helper('affiliatecommission')->__('Total Group Sales'),
          'align'     =>'left',
          'index'     => 'groupsales',
      ));
		
		$store = $this->_getStore();
       
	   $this->addColumn('affiliatecommission', array(
          'header'    => Mage::helper('affiliatecommission')->__('Affiliate Commission'),
          'type'  => 'price',
           'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     => 'affiliatecommission',
      ));		

       $this->addColumn('status', array(
          'header'    => Mage::helper('affiliatecommission')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              'pending' => 'Pending',
       	      'distributed' => 'Distributed',
          ),
      ));
      
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('affiliatecommission')->__('Action'),
                'index'     =>'id',
                'sortable' =>false,
                'filter'   => false,
                'no_link' => true,
                'renderer' => 'affiliatecommission/adminhtml_affiliatecommission_grid_renderer_action'
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('affiliatecommission')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('affiliatecommission')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('affiliatecommission_id');
        $this->getMassactionBlock()->setFormFieldName('affiliatecommission');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('affiliatecommission')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('affiliatecommission')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('affiliatecommission/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('affiliatecommission')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('affiliatecommission')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return;
  }

}