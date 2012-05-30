<?php

class Vits_Groupsales_Block_Adminhtml_Groupsales_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('groupsalesGrid');
      $this->setDefaultSort('groupsales_id');
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
      $collection = Mage::getModel('groupsales/groupsales')->getCollection();
      $this->setCollection($collection);
      parent::_prepareCollection();
       $this->getCollection()
       ->addCustomerName();
        return $this;
  }

  protected function _prepareColumns()
  {
      $this->addColumn('groupsales_id', array(
          'header'    => Mage::helper('groupsales')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'groupsales_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('groupsales')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));
      
      
      $this->addColumn('groupsales_month', array(
          'header'    => Mage::helper('groupsales')->__('Month'),
          'align'     =>'left',
          'index'     => 'groupsales_month',
      ));
      
      
      $this->addColumn('before_groupsales', array(
          'header'    => Mage::helper('groupsales')->__('Personal BV'),
          'align'     =>'left',
          'index'     => 'before_groupsales',
      ));
      
	   $this->addColumn('groupsales', array(
          'header'    => Mage::helper('groupsales')->__('Group Sales'),
          'align'     =>'left',
          'index'     => 'groupsales',
      ));
	  
       $this->addColumn('after_groupsales', array(
          'header'    => Mage::helper('groupsales')->__('Final Groupsales'),
          'align'     =>'left',
          'index'     => 'after_groupsales',
      ));

	  $store = $this->_getStore();
       
	   $this->addColumn('commission', array(
          'header'    => Mage::helper('groupsales')->__('Commission'),
		  'type'  => 'price',
		  'currency_code' => $store->getBaseCurrency()->getCode(),
          'align'     =>'left',
          'index'     => 'commission',
      ));
	  
	  $this->addColumn('gl_bonus', array(
          'header'    => Mage::helper('groupsales')->__('EL Bonus'),
          'align'     =>'right',
          'index'     => 'gl_bonus',
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('groupsales')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('groupsales')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('groupsales')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('groupsales')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('groupsales_id');
        $this->getMassactionBlock()->setFormFieldName('groupsales');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('groupsales')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('groupsales')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('groupsales/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('groupsales')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('groupsales')->__('Status'),
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