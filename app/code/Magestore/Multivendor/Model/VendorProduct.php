<?php
namespace Magestore\Multivendor\Model;
/**
* Class VendorProduct
* @package Magestore\Multivendor\Model
*/
class VendorProduct extends \Magento\Framework\Model\AbstractModel
{
    /**
    * @param \Magento\Framework\Model\Context $context
    * @param \Magento\Framework\Registry $registry
    * @param ResourceModel\VendorProduct $resource
    * @param ResourceModel\VendorProduct\Collection $resourceCollection
    */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magestore\Multivendor\Model\ResourceModel\VendorProduct
        $resource,
        \Magestore\Multivendor\Model\ResourceModel\VendorProduct\Collection
        $resourceCollection
    )
    {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }
}
