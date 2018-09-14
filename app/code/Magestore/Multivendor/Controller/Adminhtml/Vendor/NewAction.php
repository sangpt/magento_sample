<?php
namespace Magestore\Multivendor\Controller\Adminhtml\Vendor;
use Magento\Framework\Controller\ResultFactory;
/**
* Class NewAction
* @package Magestore\Multivendor\Controller\Adminhtml\Vendor
*/
class NewAction extends \Magestore\Multivendor\Controller\Adminhtml\Vendor
{
    /**
    * @return mixed
    */
    public function execute()
    {
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('edit');
    }
}
