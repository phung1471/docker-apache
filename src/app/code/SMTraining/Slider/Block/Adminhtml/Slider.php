<?php
namespace SMTraining\Slider\Block\Adminhtml;

use \Magento\Backend\Block\Widget\Grid\Container;

class Slider extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_slider';
        $this->_blockGroup = 'SMTraining_Slider';
        $this->_headerText = __('Slider');
        $this->_addButtonLabel = __('Create New Slider');
        parent::_construct();
    }
}
