<?php

namespace SMTraining\Slider\Block\Adminhtml\Slider\Edit\SlidesGrid\Renderer;

use \Magento\Framework\DataObject;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Backend\Block\Context;
use \Magento\Framework\UrlInterface;
use \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;


class SlideImage extends AbstractRenderer
{
    protected $_storeManager;


    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
    }


    public function render(DataObject $row)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if ($this->_getValue($row) != '') {
            $imageUrl = $mediaDirectory . $this->_getValue($row);
            $img = '<img src="' . $imageUrl . '" width="50" height="25"/>';
        } else {
            $img = '';
        }
        return $img;
    }
}