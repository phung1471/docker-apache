<?php
namespace SMTraining\Slider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use \Magento\Framework\Filesystem;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\MediaStorage\Model\File\UploaderFactory;
use \Magento\Framework\App\Cache\TypeListInterface;
use \Magento\Backend\Helper\Js as JsHelper;

class Save extends Action{

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    protected $_mediaDirectory;

    protected $fileSystem;

    public function __construct(
        Action\Context $context,
        TypeListInterface $cacheTypeList,
        JsHelper $jsHelper
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->jsHelper = $jsHelper;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $model = $this->_objectManager->create('SMTraining\Slider\Model\Slider');
            $id = $this->getRequest()->getParam('slider_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('You saved this slider.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the slider.' . $e->getMessage()));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}