<?php

namespace SMTraining\Slider\Model\Slide;

use SMTraining\Slider\Model\ResourceModel\Slide\CollectionFactory as slideCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use \Magento\Store\Model\StoreManagerInterface;
use SMTraining\Slider\Model\ImageUploader;

class DataProvider extends AbstractDataProvider
{
    protected $storeManager;
    protected $imageUploader;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        slideCollectionFactory $slideCollectionFactory,
        StoreManagerInterface $storeManager,
        ImageUploader $imageUploader,
        array $meta = [],
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->imageUploader =$imageUploader;
        $this->collection = $slideCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $slide) {
            $imageName = str_replace('images/', '', $slide->getData('image'));
            $_data[0] = [
                'name' => $imageName,
                'url' => $slide->getImageUrl()
            ];

            $slide->setData('image', $_data);
            $this->loadedData[$slide->getId()] = $slide->getData();
        }
        return $this->loadedData;
    }
}