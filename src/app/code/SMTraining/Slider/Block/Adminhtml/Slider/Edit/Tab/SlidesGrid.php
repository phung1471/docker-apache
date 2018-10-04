<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace SMTraining\Slider\Block\Adminhtml\Slider\Edit\Tab;

use \Magento\Backend\Block\Widget\Grid\Extended as GridExtended;
use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Helper\Data as HelperData;
use \SMTraining\Slider\Model\ResourceModel\Slide\Collection as SlideCollection;
use \Magento\Framework\Registry;

/**
 * Adminhtml staff edit form
 */
class SlidesGrid extends GridExtended
{

    /**
     * @var SlideCollection
     */
    protected $_slideCollection;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @param Context $context
     * @param HelperData $helperData
     * @param SlideCollection $slideCollection
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        SlideCollection $slideCollection,
        Registry $registry,
        array $data = []
    ) {
        $this->_slideCollection = $slideCollection;
        $this->_registry = $registry;
        $this->setEmptyText(__('No Slides Found'));

        parent::__construct($context, $helperData, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $sliderId = $this->_registry->registry('slider')->getId();
        $slideCollection = $this->_slideCollection
            ->addFieldToSelect('*')
            ->addFilter('slider_id', $sliderId);
        $this->setCollection($slideCollection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn(
            'slide_id',
            [
                'header' => __('ID'),
                'index' => 'slide_id',
            ]
        );
        $this->addColumn(
            'slide_name',
            [
                'header' => __('Name'),
                'index' => 'name',
            ]
        );
        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'index' => 'image',
                'renderer'  => '\SMTraining\Slider\Block\Adminhtml\Slider\Edit\SlidesGrid\Renderer\SlideImage',
                'filter'    => false,
                'sortable'  => false
            ]
        );
        $this->addColumn(
            'slide_enable',
            [
                'header' => __('Enable'),
                'index' => 'enable',
                'type' => 'options',
                'options' => [
                    1 => 'Yes',
                    0 => 'No'
                ],
            ]
        );

        $this->addColumn('action',
            [
                'header'    => __('Action'),
                'width'     => '25px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => [
                    [
                        'caption' => __('Edit'),
                        'url'     => array('base'=>'smtraining/slide/edit'),
                        'field'   => 'id',
                    ]
                ],
                'filter'    => false,
                'sortable'  => false
            ]
        );

        return $this;
    }

}