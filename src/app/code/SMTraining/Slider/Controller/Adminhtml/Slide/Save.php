<?php
namespace SMTraining\Slider\Controller\Adminhtml\Slide;

use \Magento\Backend\App\Action;
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

    protected $_fileUploaderFactory;

    public function __construct(
        Action\Context $context,
        TypeListInterface $cacheTypeList,
        JsHelper $jsHelper,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->jsHelper = $jsHelper;
        $this->_fileUploaderFactory = $uploaderFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $model = $this->_objectManager->create('SMTraining\Slider\Model\Slide');
            $id = $this->getRequest()->getParam('slide_id');
            if ($id) {
                $model->load($id);
                $data['image'] = $model->getImage();
            }

//            echo '<pre>';
//            print_r($data);
//            echo '</pre>';
//            die;

//            $file = $this->getRequest()->getFiles('image');
//            if (isset($file) && $file['name'] != '') {
                $uploadPath = 'images/';
//                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
//                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything
//                $uploader->setAllowRenameFiles(true);
//                $uploader->setAllowCreateFolders(true);
//                $uploader->setFilesDispersion(false);
//                $path = $this->_mediaDirectory->getAbsolutePath($uploadPath);
//                $uploader->save($path);
//
//                $data['image'] = $uploadPath . $uploader->getUploadedFileName();
                $data['image'] = $uploadPath . $data['image'][0]['name'];
//            }

            $model->setData($data);

            try {
                $model->save();
                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('You saved this slide.'));
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
                $this->messageManager->addException($e, __('Something went wrong while saving the slide.' . $e->getMessage()));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}