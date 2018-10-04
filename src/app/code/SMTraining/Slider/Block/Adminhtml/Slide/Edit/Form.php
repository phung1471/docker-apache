<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace SMTraining\Slider\Block\Adminhtml\Slide\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\Data\FormFactory;
use \Magento\Store\Model\System\Store;
use \Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use \Magento\Config\Model\Config\Source\Yesno;
use \SMTraining\Slider\Model\ResourceModel\Slider\Collection as SliderCollection;

/**
 * Adminhtml staff edit form
 */
class Form extends Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_status;

    protected $_wysiwygConfig;

    protected $_YesnoOption;

    protected $_sliderCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        SliderCollection $collection,
        Store $systemStore,
        WysiwygConfig $wysiwygConfig,
        Yesno $yesnoOption,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_YesnoOption = $yesnoOption;
        $this->_sliderCollection = $collection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('post_form');
        $this->setTitle(__('Post Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \SMTraining\Slider\Model\Slider $model */
        $model = $this->_coreRegistry->registry('slide');
        $id = $this->getRequest()->getParam('id');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $form->setHtmlIdPrefix('slide_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('slide_id', 'hidden', ['name' => 'slide_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );

        $fieldset->addField(
            'slider_id',
            'select',
            [
                'label' => __('Slider'),
                'title' => __('Slider'),
                'name' => 'slider_id',
                'options' => $this->_sliderCollection->selectResource()
            ]
        );

        $fieldset->addField('image', 'image', array(
            'label' => __('Upload Image'),
            'required' => false,
            'name' => 'image',
        ));
        $fieldset->addField(
            'enable',
            'select',
            [
                'label' => __('Enable'),
                'title' => __('Enable'),
                'name' => 'enable',
                'options' => $this->_YesnoOption->toArray()
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}