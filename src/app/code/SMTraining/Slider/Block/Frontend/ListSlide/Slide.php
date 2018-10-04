<?php
namespace SMTraining\Slider\Block\Frontend\ListSlide;

use \Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use \SMTraining\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use \SMTraining\Slider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;
use \SMTraining\Slider\Model\SliderFactory;
use \Magento\Framework\Filesystem;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Slide extends Template{

    protected $_sliderCollectionFactory = null;
    protected $_slideCollectionFactory = null;
    protected $_coreRegistry;
    protected $_sliderFactory;
    protected $_mediaDirectory;

    /**@var \SMTraining\Slider\Model\Slider */
    protected $_slider;

    /**
     * Constructor
     *
     * @param Context $context
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param SlideCollectionFactory $slideCollectionFactory
     * @param SliderFactory $sliderFactory
     * @param Registry $coreRegistry
     * @param Filesystem $filesystem
     * @param array $data
     */
    public function __construct(
        Context $context,
        SliderCollectionFactory $sliderCollectionFactory,
        SlideCollectionFactory $slideCollectionFactory,
        SliderFactory $sliderFactory,
        Registry $coreRegistry,
        Filesystem $filesystem,
        array $data = []
    ) {
        $this->_sliderCollectionFactory = $sliderCollectionFactory;
        $this->_slideCollectionFactory = $slideCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_sliderFactory = $sliderFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        parent::__construct($context, $data);
    }

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set($this->getSlider()->getName());
        return parent::_prepareLayout();
    }

    /**
     * Lazy loads the requested slider
     * @return \SMTraining\Slider\Model\Slider
     * @return Boolean
     * @throws LocalizedException
     */
    public function getSlider(){
        if($this->_slider === null){
            $slider = $this->_sliderFactory->create()
            ->load($this->_getSliderId());

            if(!$slider->getId()){
//                throw new LocalizedException(__('Slider not found'));
                return false;
            }
            $this->_slider = $slider;
        }

        return $this->_slider;
    }

    /**
     * @return \SMTraining\Slider\Model\ResourceModel\Slide\Collection
     */
    public function getSlides()
    {
        $slideCollection = $this->_slideCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFilter('slider_id', $this->_getSliderId())
            ->addFilter('enable', 1);

        return $slideCollection->load();
    }

    /**
     * Retrieves the slider id from the registry
     * @return int
     */
    protected function _getSliderId()
    {
        return (int) $this->_coreRegistry->registry('slider_id');
    }

    /**
     * Get current store name.
     *
     * @return string
     */
    public function getCurrentStoreID()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getFullImagePath($img){
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $mediaDirectory. $img;
    }
}