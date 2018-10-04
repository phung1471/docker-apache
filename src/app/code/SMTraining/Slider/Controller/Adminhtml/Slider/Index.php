<?php

namespace SMTraining\Slider\Controller\Adminhtml\Slider;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory = false;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

//        $resultPage->setActiveMenu('Mageplaza_HelloWorld::mage_helloworld');
//        $resultPage->addBreadcrumb(__('CMS'), __('CMS'));
//        $resultPage->addBreadcrumb(__('Manage Post'), __('Manage Post'));
//        $resultPage->getConfig()->getTitle()->prepend(__('Manage Post'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SMTraining_Slider::sliders_manager');
    }

}