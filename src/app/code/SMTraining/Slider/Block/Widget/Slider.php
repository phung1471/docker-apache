<?php
namespace SMTraining\Slider\Block\Widget;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use \Magento\Framework\View\Element\Template\Context;
use \SMTraining\Slider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;

class Slider extends Template implements BlockInterface {

    protected $_template = "widget/slider.phtml";

    protected $_slideCollectionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param SlideCollectionFactory $slideCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        SlideCollectionFactory $slideCollectionFactory,
        array $data = []
    )
    {
        $this->_slideCollectionFactory = $slideCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getSlides(){
        $slideCollection = $this->_slideCollectionFactory->create();
        $slideCollection ->addFilter('slider_id', (int)$this->getData('slider_id'))
            ->addFilter('enable', 1);

        return $slideCollection->load();
    }

    /**
     * @return int Current_Store_ID
     */
    public function getCurrentStoreID(){
        return $this->_storeManager->getStore()->getId();
    }

    public function getFullImagePath($img){
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $mediaDirectory. $img;
    }

    /**
     * @param string $asset
     * @return string
     */
    //This function will be used to get the css/js file.
    function getAssetUrl($asset) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $assetRepository = $objectManager->get('Magento\Framework\View\Asset\Repository');
        return $assetRepository->createAsset($asset)->getUrl();
    }
}