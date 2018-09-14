<?php
namespace Magestore\Multivendor\Model;
/**
* Class Vendor
* @package Magestore\Multivendor\Model
*/
class Vendor extends \Magento\Framework\Model\AbstractModel
{
    /**
    * @param \Magento\Framework\Model\Context $context
    * @param \Magento\Framework\Registry $registry
    * @param ResourceModel\Vendor $resource
    * @param ResourceModel\Vendor\Collection $resourceCollection
    */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magestore\Multivendor\Model\ResourceModel\Vendor $resource,
        \Magestore\Multivendor\Model\ResourceModel\Vendor\Collection
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
