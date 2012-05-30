<?php

class Vits_Companypool_Block_Adminhtml_Companypool_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
      $this->setId('companypoolGrid');
      $this->setDefaultSort('companypool_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('companypool/companypool')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('companypool_id', array(
          'header'    => Mage::helper('companypool')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'companypool_id',
      ));

      $this->addColumn('company_time', array(
          'header'    => Mage::helper('companypool')->__('Time'),
          'align'     =>'left',
          'index'     => 'company_time',
      ));
      
      $this->addColumn('company_real_amount', array(
          'header'    => Mage::helper('companypool')->__('Company Real Amount'),
          'align'     =>'left',
          'index'     => 'company_real_amount',
      ));
      
      $this->addColumn('company_edit_amount', array(
          'header'    => Mage::helper('companypool')->__('Company Edit Emount'),
          'align'     =>'left',
          'index'     => 'company_edit_amount',
      ));
       $this->addColumn('status', array(
          'header'    => Mage::helper('companypool')->__('Status'),
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
                'header'    => Mage::helper('companypool')->__('Action'),
                'index'     =>'id',
                'sortable' =>false,
                'filter'   => false,
                'no_link' => true,
                'renderer' => 'companypool/adminhtml_companypool_grid_renderer_action'
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('companypool')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('companypool')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('companypool_id');
        $this->getMassactionBlock()->setFormFieldName('companypool');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('companypool')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('companypool')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('companypool/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('companypool')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('companypool')->__('Status'),
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