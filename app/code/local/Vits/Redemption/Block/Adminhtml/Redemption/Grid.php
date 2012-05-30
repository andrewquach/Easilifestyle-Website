<?php

class Vits_Redemption_Block_Adminhtml_Redemption_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('redemptionGrid');
      $this->setDefaultSort('redemption_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('redemption/redemption')->getCollection()
      					->addCustomerName();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('redemption_id', array(
          'header'    => Mage::helper('redemption')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'redemption_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('redemption')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));
       $this->addColumn('product_name', array(
          'header'    => Mage::helper('redemption')->__('Product'),
          'align'     =>'left',
          'index'     => 'product_name',
      ));
      $this->addColumn('created_time', array(
			'header'    => Mage::helper('redemption')->__('Order Time'),
			'width'     => '150px',
			'index'     => 'created_time',
      		'type'      => 'date',
      		'format'	=> 'YYYY-MM-dd',
      ));
      $this->addColumn('qty', array(
          'header'    => Mage::helper('redemption')->__('Qty'),
          'align'     =>'left',
          'index'     => 'qty',
      ));
       $this->addColumn('gl_price', array(
          'header'    => Mage::helper('redemption')->__('EL  Price'),
          'align'     =>'left',
          'index'     => 'gl_price',
      ));
      $this->addColumn('total_gl', array(
          'header'    => Mage::helper('redemption')->__('Total EL'),
          'align'     =>'left',
          'index'     => 'total_gl',
      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('redemption')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              'pending' => 'Pending',
              'processing' => 'Processing',
       		  'complete' => 'Completed',
       	      //'cancel' => 'Cancelled',
          ),
      ));
	  
	  $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('redemption')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getCustomerId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('redemption')->__('View Customer Details'),
                        'url'       => array('base'=> '../index.php/admin/customer/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('redemption')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('redemption')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('redemption_id');
        $this->getMassactionBlock()->setFormFieldName('redemption');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('redemption')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('redemption')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('redemption/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('redemption')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('redemption')->__('Status'),
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