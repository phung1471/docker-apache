<?php
namespace SMTraining\Slider\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use \SMTraining\Slider\Model\Slider as SliderModel;

class Slider extends AbstractDb{

    const MAIN_TABLE = 'sm_cms_slider';
    const ID_FIELD_NAME = 'slider_id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    /**
     * Process author data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        parent::_beforeDelete($object);
        $condition = ['slider_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('sm_cms_slider_store'), $condition);
        return $this;
    }

    /**
     * Assign author to store views
     *
     * @param AbstractModel|SliderModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        parent::_afterSave($object);
        $this->saveStoreRelation($object);

        return $this;
    }

    /**
     * @param SliderModel $slider
     * @return $this
     */
    protected function saveStoreRelation(SliderModel $slider)
    {
        $oldStores = $this->lookupStoreIds($slider->getId());
        $newStores = (array)$slider->getStoreId();
        if (empty($newStores)) {
            $newStores = (array)$slider->getStoreId();
        }
        $table = $this->getTable('sm_cms_slider_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                'slider_id = ?' => (int)$slider->getId(),
                'store_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'slider_id' => (int)$slider->getId(),
                    'store_id' => (int)$storeId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
        return $this;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $slider_id
     * @return array
     */
    public function lookupStoreIds($slider_id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('sm_cms_slider_store'),
            'store_id'
        )->where(
            'slider_id = ?',
            (int)$slider_id
        );
        return $adapter->fetchCol($select);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \SMTraining\Slider\Model\Slider $object
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId()
            ];
            $select->join(
                [
                    'sm_cms_slider_store' => $this->getTable('sm_cms_slider_store')
                ],
                $this->getMainTable() . '.slider_id = sm_cms_slider_store.slider_id',
                []
            )
                ->where(
                    'sm_cms_slider_store.store_id IN (?)',
                    $storeIds
                )
                ->order('sm_cms_slider_store.store_id DESC')
                ->limit(1);
        }
        return $select;
    }

    /**
     * Perform actions after object load
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {
        parent::_afterLoad($object);

        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_ids', $stores);
        }

        return $this;
    }

//    public function saveImportData(array $data){
//        if (!empty($data)) {
//            $columns = array('store_id'); //add here all your column names except the autoincrement column.
//            $this->_getWriteAdapter()->insertOnDuplicate($this->getMainTable(), $data, $columns);
//        }
//        return $this;
//    }

}