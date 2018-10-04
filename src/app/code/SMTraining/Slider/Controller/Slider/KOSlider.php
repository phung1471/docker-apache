<?php

namespace SMTraining\Slider\Controller\Slider;

//use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Store\Model\StoreManagerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use \Magento\Framework\Data\Form\FormKey;
use SMTraining\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;


class KOSlider extends Action
{
    protected $imageHelper;
    protected $listProduct;
    protected $_storeManager;
    protected $sliderCollectionFactory;

    public function __construct(
        Context $context,
        FormKey $formKey,
        SliderCollectionFactory $sliderCollectionFactory,
        StoreManagerFactory $storeManager,
        Image $imageHelper
    )
    {
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->imageHelper = $imageHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $sliders = $this->getSliders();
        $responseData = [];
        foreach ($sliders->getItems() as $slider){
            $responseData[] = $this->prepareJSONData($slider);
        }

        echo json_encode($responseData);

        return;
    }

    /**
     * @return \SMTraining\Slider\Model\ResourceModel\Slider\Collection
     */
    public function getSliders()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = 2;
        $storeManager = $this->_storeManager->create();

        /** @var \SMTraining\Slider\Model\ResourceModel\Slider\Collection $postCollection */
        $sliderCollection = $this->sliderCollectionFactory->create();
        $sliderCollection->getSelect()
            ->joinLeft(
                'sm_cms_slider_store',
                '`main_table`.`slider_id` = `sm_cms_slider_store`.`slider_id`'
            )->where(
                '`sm_cms_slider_store`.`store_id` IN (' . $storeManager->getStore()->getId() . ', 0) 
                AND `main_table`.`enable` = 1'
            );
        $sliderCollection->setPageSize($pageSize);
        $sliderCollection->setCurPage($page);
        $sliderCollection->load();

        return $sliderCollection;
    }

    public function prepareJSONData($slider)
    {
        return [
            'slider_id' => $slider->getSliderId(),
            'name' => $slider->getName(),
            'link' => '/sliders/slider/view/id/' . $slider->getSliderId()
        ];
    }
}