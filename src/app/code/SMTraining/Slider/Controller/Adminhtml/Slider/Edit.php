<?php

namespace SMTraining\Slider\Controller\Adminhtml\Slider;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Registry;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SMTraining_Slider::sliders_manager');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SMTraining_Slider::slider')
            ->addBreadcrumb(__('Slider'), __('Slider'))
            ->addBreadcrumb(__('Slider Infomation'), __('Slider Infomation'));
        return $resultPage;
    }

    /**
     * Edit Staff
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Mageplaza\HelloWorld\Model\Grid $slider */
        $slider = $this->_objectManager->create('SMTraining\Slider\Model\Slider');
        if ($id) {
            $slider->load($id);
            if (!$slider->getId()) {
                $this->messageManager->addError(__('This slider no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $slider->setData($data);
        }

        $this->_coreRegistry->register('slider', $slider);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit') : __('New'),
            $id ? __('Edit') : __('New')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Slider Grid'));
        $resultPage->getConfig()->getTitle()
            ->prepend($slider->getId() ? $slider->getName() : __('New Slider'));

        return $resultPage;
    }
}