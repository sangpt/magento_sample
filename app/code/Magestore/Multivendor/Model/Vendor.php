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
        \Magestore\Multivendor\Model\ResourceModel\Vendor\Collection $resourceCollection,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory
    )
    {
        $this->_dateFactory = $dateFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt($this->_dateFactory->create()->gmtDate());
        } else {
            $this->setUpdatedAt($this->_dateFactory->create()->gmtDate());
        }
        return parent::beforeSave();
    }
}
