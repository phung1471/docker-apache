<?php

namespace SMTraining\Slider\Controller\Slider;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use SMTraining\Slider\Controller\MiddleWare;

class View extends MiddleWare
{

    /**
     * Core registry
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Prints the blog from informed order id
     * @return \Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        parent::execute();
        $this->_coreRegistry->register('slider_id', (int) $this->_request->getParam('id'));
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }

}