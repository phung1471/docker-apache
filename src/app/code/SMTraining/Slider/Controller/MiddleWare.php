<?php
namespace SMTraining\Slider\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\NotFoundException;

class MiddleWare extends Action{

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 * @throws NotFoundException
	 */
	public function execute()
    {
        $id = $this->getRequest()->getParam('id');

//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
//        $connection = $resource->getConnection();

//        $select = $connection->select()
//            ->from('sm_cms_slider_store')->where(
//                'store_id IN (' . $this->getCurrentStoreID() . ', 0)  AND slider_id ='. $id
//            );
//        $slider = $connection->fetchAll($select);

        $slider = $this->_objectManager->create('SMTraining\Slider\Model\ResourceModel\Slider\Collection');

        if($id){
            $slider->getSelect()
                ->joinLeft(
                    'sm_cms_slider_store',
                    '`main_table`.`slider_id` = `sm_cms_slider_store`.`slider_id`'
                )->where(
                    '`sm_cms_slider_store`.`store_id` IN (' . $this->getCurrentStoreID() . ', 0)
                AND `main_table`.`enable` = 1 AND `main_table`.`slider_id` = ' . $id
                );
            if(!count($slider)){
                throw new NotFoundException(__('Page not found.'));
            }
        }else{
            throw new NotFoundException(__('Page not found.'));
        }
    }

	/**
	 * @return mixed
	 */
	public function getCurrentStoreID(){
        $store = $this->_objectManager->create('Magento\Store\Model\StoreManager');
        return $store->getStore()->getId();
    }
}