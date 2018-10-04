<?php
namespace SMTraining\Slider\Ui\Component\Listing\GridSlider\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    /** Url path */
    const GRID_URL_PATH_EDIT = 'smtraining/slider/edit';
    const GRID_URL_PATH_DELETE = 'smtraining/slider/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::GRID_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
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
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['slider_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['slider_id']]),
                        'label' => __('Edit')
                    ];
//                    $item[$name]['delete'] = [
//                        'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_DELETE, ['id' => $item['slider_id']]),
//                        'label' => __('Delete'),
//                        'confirm' => [
//                            'title' => __('Delete "${ $.$data.name }"'),
//                            'message' => __('Are you sure you wan\'t to delete a "${ $.$data.name }" record?')
//                        ]
//                    ];
                }
            }
        }
        return $dataSource;
    }
}