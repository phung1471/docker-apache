<?php

namespace SMTraining\Slider\Model\ResourceModel\Slide;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection{

    const MODEL = '\SMTraining\Slider\Model\Slide';
    const RESOURCE_MODEL = '\SMTraining\Slider\Model\ResourceModel\Slide';
    const ID_FIELD_NAME = 'slide_id';

    protected $_idFieldName = self::ID_FIELD_NAME;

    protected function _construct()
    {
        $this->_init(self::MODEL, self::RESOURCE_MODEL);
    }
}