<?php
namespace SMTraining\Slider\Block\Adminhtml;

use \Magento\Backend\Block\Widget\Grid\Container;

class Slide extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_slide';
        $this->_blockGroup = 'SMTraining_Slider';
        $this->_headerText = __('Slide');
        $this->_addButtonLabel = __('Create New Slide');
        parent::_construct();
    }
}
