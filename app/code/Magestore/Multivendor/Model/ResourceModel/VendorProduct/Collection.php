<?php
namespace Magestore\Multivendor\Model\ResourceModel\VendorProduct;
/**
* Class Collection
* @package Magestore\Multivendor\Model\ResourceModel\Vendor
*/
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'vendor_product_id';
    public function _construct(){
        parent::_construct();
        $this->_init(
            'Magestore\Multivendor\Model\VendorProduct',
            'Magestore\Multivendor\Model\ResourceModel\VendorProduct'
        );
    }
}
