<?php

namespace Toptal\Blog\Controller\Adminhtml\Index;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory as ResultPageFactory;

class Index extends Action
{
	protected $resultPageFactory = false;

	public function __construct(
		Context $context,
		ResultPageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Blog')));

		return $resultPage;
	}
}