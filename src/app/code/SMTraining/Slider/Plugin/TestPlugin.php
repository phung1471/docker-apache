<?php
namespace SMTraining\Slider\Plugin;

use SMTraining\Slider\Model\Slider;

class TestPlugin{
    /**
     * @param Slider $subject
     * @param $result
     * @return string
     */
    public function afterGetName(Slider $subject, $result){
        return $result . ' - plugin';
    }
}