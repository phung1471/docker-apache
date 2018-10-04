<?php
namespace SMTraining\Slider\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Slide extends AbstractDb{

    const MAIN_TABLE = 'sm_cms_slider_slide';
    const ID_FIELD_NAME = 'slide_id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}