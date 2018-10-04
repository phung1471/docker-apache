<?php

namespace SMTraining\Slider\Ui\Component\Listing\GridSlide\Column;

use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\UrlInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \SMTraining\Slider\Model\Slider as SliderModel;

class Slider extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $_storeManager;

    protected $_sliderModel;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        SliderModel $sliderModel,
        array $components = [],
        array $data = [],
        StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->_sliderModel = $sliderModel;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');//echo $fieldName;
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$fieldName] = $this->getSliderName($item[$fieldName]);
            }
        }

        return $dataSource;
    }

    /**
     * @var int $slider_id
     * @return string
     */
    public function getSliderName($slier_id)
    {
        /** @var \SMTraining\Slider\Model\Slider $slider */
        $slider = ObjectManager::getInstance()
            ->create('SMTraining\Slider\Model\Slider')
            ->load($slier_id);

        return $slider->getName();
    }
}