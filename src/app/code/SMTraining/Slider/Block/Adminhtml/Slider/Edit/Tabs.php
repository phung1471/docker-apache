<?php

namespace SMTraining\Slider\Block\Adminhtml\Slider\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('slider_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Slider Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'slider_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'SMTraining\Slider\Block\Adminhtml\Slider\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );

        $this->addTab(
            'slider_slides',
            [
                'label' => __('Slides'),
                'title' => __('Slides'),
                'content' => $this->getLayout()->createBlock(
                    'SMTraining\Slider\Block\Adminhtml\Slider\Edit\Tab\SlidesGrid'
                )->toHtml(),
            ]
        );

        return parent::_beforeToHtml();
    }
}