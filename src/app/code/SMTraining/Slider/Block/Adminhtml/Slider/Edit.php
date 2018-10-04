<?php

namespace SMTraining\Slider\Block\Adminhtml\Slider;

use \Magento\Backend\Block\Widget\Form\Container;
use \Magento\Backend\Block\Widget\Context;
use \Magento\Framework\Registry;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id'; //request param
        $this->_blockGroup = 'SMTraining_Slider';
        $this->_controller = 'adminhtml_slider';

        parent::_construct();

//        if ($this->_isAllowedAction('SMTraining_Slider::save')) {
        $this->buttonList->update('save', 'label', __('Save Slider'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
//        } else {
//            $this->buttonList->remove('save');
//        }

//        if ($this->_isAllowedAction('SMTraining_Slider::grid_delete')) {
        $this->buttonList->update('delete', 'label', __('Delete Slider'));
//        } else {
//            $this->buttonList->remove('delete');
//        }

        if ($this->_coreRegistry->registry('slider')->getId()) {
            $this->buttonList->remove('reset');
        }
    }

    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('slider_grid')->getId()) {
            return __("Edit Slider '%1'", $this->escapeHtml($this->_coreRegistry->registry('slider')->getTitle()));
        } else {
            return __('New Slider');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('slider/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}