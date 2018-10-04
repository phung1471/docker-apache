<?php
namespace SMTraining\Slider\Controller\Slider;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\Action\Context;
//use SMTraining\Slider\Controller\MiddleWare;

class KO extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $storeManager;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Prints the blog from informed order id
     * @return \Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }
}