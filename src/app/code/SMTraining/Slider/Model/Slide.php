<?php

namespace SMTraining\Slider\Model;

use \Magento\Framework\Model\AbstractModel;
use \Magento\Store\Model\StoreManagerInterface;

class Slide extends AbstractModel
{

    const RESOURCE_MODEL = '\SMTraining\Slider\Model\ResourceModel\Slide';

    protected $storeManager;
    protected $imageUploader;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(self::RESOURCE_MODEL);
    }

    /**
     * Slide constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param StoreManagerInterface $storeManager
     * @param \SMTraining\Slider\Model\ImageUploader $imageUploader
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

}