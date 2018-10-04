<?php
namespace SMTraining\Slider\Controller\Adminhtml\Slider;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\Exception\LocalizedException;
use \Exception;

class Delete extends Action{

    /**
     * @param Action\Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if($id){
            try{
                $model = $this->_objectManager->create('SMTraining\Slider\Model\Slider');
                $model->load($id);
                $model->delete();
                $this->_redirect('smtraining/slider/');
                $this->messageManager->addSuccess(__('Delete slider successfull.'));
            }catch (LocalizedException $e){
                $this->messageManager->addError($e->getMessage());
            }catch (Exception $e){
                $this->messageManager->addError(
                    __('We can\'t delete this slider right now. Please review the log and try again.' . $e->getMessage())
                );
            }

        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SMTraining_Slider::sliders_manager');
    }
}