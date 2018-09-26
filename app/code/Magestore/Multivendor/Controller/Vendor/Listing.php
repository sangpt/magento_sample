<?php
namespace Magestore\Multivendor\Controller\Vendor;

class Listing extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
