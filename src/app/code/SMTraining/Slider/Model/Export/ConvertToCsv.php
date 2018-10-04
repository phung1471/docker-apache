<?php
namespace SMTraining\Slider\Model\Export;

use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Ui\Model\Export\ConvertToCsv as UiConvertToCsv;

class ConvertToCsv extends UiConvertToCsv{

    //const EXPORT_COLUMNS = ['name', 'enable', 'location', 'store_id'];
    /**
     * Returns CSV file
     *
     * @return array
     * @throws LocalizedException
     */
    public function getCsvFile()
    {
        $component = $this->filter->getComponent();

        $name = md5(microtime());
        $file = 'export/'. $component->getName() . $name . '.csv';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();
        $dataProvider = $component->getContext()->getDataProvider();
        $fields = $this->metadataProvider->getFields($component);
        //$fields = self::EXPORT_COLUMNS;
        //$fields = array_intersect(self::EXPORT_COLUMNS, $fields);
        $options = $this->metadataProvider->getOptions();
        if(isset($options['store_id'])){
            unset($options['store_id']);
        }
        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();
        $headers = $this->metadataProvider->getHeaders($component);
        $stream->writeCsv($this->renameHeaders($headers));
        $i = 1;
        $searchCriteria = $dataProvider->getSearchCriteria()
            ->setCurrentPage($i)
            ->setPageSize($this->pageSize);
        $totalCount = (int) $dataProvider->getSearchResult()->getTotalCount();
        while ($totalCount > 0) {
            $items = $dataProvider->getSearchResult()->getItems();
            foreach ($items as $item) {
                $item = $this->convertArr2String($item);
                $this->metadataProvider->convertDate($item, $component->getName());
                $stream->writeCsv($this->metadataProvider->getRowData($item, $fields, $options));
            }
            $searchCriteria->setCurrentPage(++$i);
            $totalCount = $totalCount - $this->pageSize;
        }
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }

    /**
     * @param DocumentInterface $item
     * @return DocumentInterface
     */
    public function convertArr2String(DocumentInterface $item){
        $store_ids = $item->getCustomAttribute('store_id')->getValue();
        if(is_array($store_ids)){
            $item->setCustomAttribute('store_id', implode(",", $store_ids));
        }

        return $item;
    }

    /**
     * @param array $headers
     * @return array
     */
    public function renameHeaders(array $headers){
        foreach($headers as $key => $name){
            $name = str_replace(' ', '_', $name);
            if($name == 'ID') $name = 'slider_id';
            $headers[$key] = strtolower($name);
        }
        return $headers;
    }
}