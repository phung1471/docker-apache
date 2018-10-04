<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace SMTraining\Slider\Block\Adminhtml\Slider\Edit;

use \Magento\Backend\Block\Widget\Form\Generic as FormGeneric;

/**
 * Adminhtml staff edit form
 */
class Form extends FormGeneric
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        parent::_prepareForm();

        return $this;
    }
}