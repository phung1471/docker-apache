<?php

namespace SMTraining\Slider\Model\ResourceModel\Slider;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    const MODEL = '\SMTraining\Slider\Model\Slider';
    const RESOURCE_MODEL = '\SMTraining\Slider\Model\ResourceModel\Slider';
    const ID_FIELD_NAME = 'slider_id';
    const MAP_ID = 'main_table.slider_id';

    protected $_idFieldName = self::ID_FIELD_NAME;

    protected function _construct()
    {
        $this->_init(self::MODEL, self::RESOURCE_MODEL);
        $this->_map['fields']['slider_id'] = self::MAP_ID;
//        $this->_map['fields']['store_id'] = 'store_table.store_id';
    }

    /**
     * prepare Collection (data for column) for Grid
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()
            ->joinLeft(
                ['left_table' => $this->getTable('sm_cms_slider_store')],
                'main_table.slider_id = left_table.slider_id',
                ['store_id', 'slider_id']
            )->group('main_table.slider_id');
        return $this;
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray(self::ID_FIELD_NAME, 'name');
    }

    public function selectResource()
    {
        $model = $this->getItems();
        $resourceArr = [];
        foreach ($model as $item) {
            $resourceArr[$item->getId()] = $item->getName();
        }

        return $resourceArr;
    }

    protected function _afterLoad()
    {
        $this->performAfterLoad('sm_cms_slider_store', 'slider_id');
        return parent::_afterLoad();
    }

    protected function performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues('slider_id');
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['sm_cms_slider_store' => $this->getTable($tableName)])
                ->where('sm_cms_slider_store.' . $linkField . ' IN (?)', $linkedIds);

            // @codingStandardsIgnoreStart
            $result = $connection->fetchAll($select);

            // @codingStandardsIgnoreEnd
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }
                foreach ($this as $item) {
                    $linkedId = $item->getData('slider_id');
                    if (isset($storesData[$linkedId])) {
                        $item->setData('store_id', $storesData[$linkedId]);
                    }
                }
            }
        }
    }

}