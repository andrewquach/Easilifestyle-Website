<?php

class Vits_Orphanpool_Block_Adminhtml_Orphanpool_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
public function __construct()
  {
      parent::__construct();
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('orphanpool/orphanpool')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('orphanpool_id', array(
          'header'    => Mage::helper('orphanpool')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'orphanpool_id',
      ));

      $this->addColumn('orphan_time', array(
          'header'    => Mage::helper('orphanpool')->__('Time'),
          'align'     =>'left',
          'index'     => 'orphan_time',
      ));
		
      
     $this->addColumn('orphan_real_amount', array(
          'header'    => Mage::helper('orphanpool')->__('Orphan Real Amount'),
          'align'     =>'left',
          'index'     => 'orphan_real_amount',
      ));
      
      $this->addColumn('orphan_edit_amount', array(
          'header'    => Mage::helper('orphanpool')->__('Orphan Edit Amount'),
          'align'     =>'left',
          'index'     => 'orphan_edit_amount',
      ));
		$this->addColumn('status', array(
          'header'    => Mage::helper('orphanpool')->__('Status'),
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
                'header'    => Mage::helper('orphanpool')->__('Action'),
                'index'     =>'id',
                'sortable' =>false,
                'filter'   => false,
                'no_link' => true,
                'renderer' => 'orphanpool/adminhtml_orphanpool_grid_renderer_action'
        ));
		$this->addExportType('*/*/exportCsv', Mage::helper('orphanpool')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('orphanpool')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('orphanpool_id');
        $this->getMassactionBlock()->setFormFieldName('orphanpool');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('orphanpool')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('orphanpool')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('orphanpool/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('orphanpool')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('orphanpool')->__('Status'),
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