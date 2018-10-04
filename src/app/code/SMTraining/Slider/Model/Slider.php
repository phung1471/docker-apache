<?php
namespace SMTraining\Slider\Model;

use \Magento\Framework\Model\AbstractModel;
//use SMTraining\Slider\Model\ResourceModel\Slider\CollectionFactory;
//use \SMTraining\Slider\Model\SliderFactory;

class Slider extends AbstractModel
{
    const RESOURCE_MODEL = '\SMTraining\Slider\Model\ResourceModel\Slider';

    protected function _construct()
    {
        $this->_init(self::RESOURCE_MODEL);
    }

    /**
     * use for save store_id into sm_sms_slider_store
     * @return array
     */
    public function getStoreId()
    {
        return $this->getData('store_ids');
    }

}