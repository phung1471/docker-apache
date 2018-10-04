<?php

namespace SMTraining\Slider\Block\Frontend;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \SMTraining\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use \SMTraining\Slider\Model\Slider as SliderModel;

class Slider extends Template
{

    protected $_sliderCollectionFactory = null;

    /**
     * Constructor
     *
     * @param Context $context
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        SliderCollectionFactory $sliderCollectionFactory,
        array $data = []
    )
    {
        $this->_sliderCollectionFactory = $sliderCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getSliders()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 1;

        /** @var \SMTraining\Slider\Model\ResourceModel\Slider\Collection $postCollection */
        $sliderCollection = $this->_sliderCollectionFactory->create();
        $sliderCollection->getSelect()
            ->joinLeft(
                'sm_cms_slider_store',
                '`main_table`.`slider_id` = `sm_cms_slider_store`.`slider_id`'
            )->where(
                '`sm_cms_slider_store`.`store_id` IN (' . $this->getCurrentStoreID() . ', 0) 
                AND `main_table`.`enable` = 1'
            );
        $sliderCollection->setPageSize($pageSize);
        $sliderCollection->setCurPage($page);
//        $sliderCollection->load();

        return $sliderCollection;
    }

    /**
     * For a given post, returns its url
     * @param SliderModel $sliderModel
     * @return string
     */
    public function getSliderUrl(
        SliderModel $sliderModel
    )
    {
        return '/sliders/slider/view/id/' . $sliderModel->getId();
    }

    /**
     * @return int Current_Store_ID
     */
    public function getCurrentStoreID()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('SMTraining Slider'));

        $this->addChild(
            'back_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Back'), 'template' => 'SMTraining_Slider::slider\pagination.phtml', 'class' => 'cancel']
        );

        if ($this->getSliders()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fme.news.pager'
            )
//                ->setTemplate('SMTraining_Slider::slider/pagination.phtml')
//                ->setAvailableLimit(array(2 => 2, 5 => 5, 10 => 10, 15 => 15))
//                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimit(2)
                ->setCollection(
                    $this->getSliders()
                );
            $this->setChild('pager', $pager);
            $this->getSliders()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}