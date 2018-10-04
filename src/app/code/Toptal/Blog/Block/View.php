<?php

namespace Toptal\Blog\Block;

use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Exception\NotFoundException;
use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\Registry;
use \Toptal\Blog\Model\Post;
use \Toptal\Blog\Model\PostFactory;
use \Toptal\Blog\Controller\Post\View as ViewAction;
use \Magento\Framework\App\Response\Http as ResponseHttp;

class View extends Template
{
	/**
	 * Core registry
	 * @var Registry
	 */
	protected $_coreRegistry;

	/**
	 * @var ResponseHttp
	 */
	protected $_responseHttp;

	/**
	 * Post
	 * @var null|Post
	 */
	protected $_post = null;

	/**
	 * PostFactory
	 * @var null|PostFactory
	 */
	protected $_postFactory = null;

	/**
	 * Constructor
	 * @param Context $context
	 * @param Registry $coreRegistry
	 * @param PostFactory $postFactory,
	 * @param ResponseHttp $responseHttp
	 * @param array $data
	 */
	public function __construct(
		Context $context,
		Registry $coreRegistry,
		PostFactory $postFactory,
		ResponseHttp $responseHttp,
		array $data = []
	) {
		$this->_postFactory = $postFactory;
		$this->_coreRegistry = $coreRegistry;
		$this->_responseHttp = $responseHttp;
		parent::__construct($context, $data);
	}

	/**
	 * Lazy loads the requested post
	 * @return Post
//	 * @throws LocalizedException
	 */
	public function getPost()
	{
		if ($this->_post === null) {
			/** @var Post $post */
			$post = $this->_postFactory->create();
			$post->load($this->_getPostId());

			if (!$post->getId()) {
//				$this->setTemplate('default/404.phtml');
//				die($this->getUrl());
				$this->_responseHttp->setRedirect($this->getBaseUrl() . '404.html');
//				throw new LocalizedException(__('Post not found'));
//				throw new NotFoundException(__('Some Exception message.'));
			}

			$this->_post = $post;
		}
		return $this->_post;
	}

	/**
	 * Retrieves the post id from the registry
	 * @return int
	 */
	protected function _getPostId()
	{
		return (int) $this->_coreRegistry->registry(
			ViewAction::REGISTRY_KEY_POST_ID
		);
	}
}