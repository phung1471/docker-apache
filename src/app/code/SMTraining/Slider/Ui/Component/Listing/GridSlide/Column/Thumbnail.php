<?php
namespace SMTraining\Slider\Ui\Component\Listing\GridSlide\Column;

use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\UrlInterface;
use \Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
//    const NAME = 'featured_image';
//    const ALT_FIELD = 'name';
//    const IMAGE_WIDTH = '70%';
//    const IMAGE_HEIGHT = '60';
//    const IMAGE_STYLE = 'display: block;margin: auto;';

    protected $_storeManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
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
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $mediaRelativePath = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imgPath = $mediaRelativePath . $item['image'];
                $item[$fieldName . '_src'] = $imgPath;
                $item[$fieldName . '_alt'] = $this->getAlt($item);
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'smtraining/slide/edit',
                    ['id' => $item['slide_id'], 'store' => $this->context->getRequestParam('store')]
                );
                $item[$fieldName . '_orig_src'] = $imgPath;
                $item[$fieldName] = "<img src=". $imgPath ." style='display: block;margin: auto;'/>";
            }
        }

        return $dataSource;
    }
}