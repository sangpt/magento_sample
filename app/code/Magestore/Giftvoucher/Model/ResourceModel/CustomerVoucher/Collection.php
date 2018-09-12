<?php
/**
 * Copyright © 2017 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Giftvoucher\Model\ResourceModel\CustomerVoucher;

/**
 * Giftvoucher Customervoucher resource collection
 *
 * @category Magestore
 * @package  Magestore_Giftvoucher
 * @module   Giftvoucher
 * @author   Magestore Developer
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'Magestore\Giftvoucher\Model\CustomerVoucher',
            'Magestore\Giftvoucher\Model\ResourceModel\CustomerVoucher'
        );
    }

    /**
     * @param $customerId
     * @param $timezone
     * @return $this
     */
    public function joinCustomer($customerId, $timezone)
    {
        $this->_isGroupSql = false;
        $this->addFieldToFilter('main_table.customer_id', $customerId);
        $voucherTable = $this->getTable('giftvoucher');
        $this->getSelect()
            ->joinleft(
                array('voucher_table' => $voucherTable),
                'main_table.voucher_id = voucher_table.giftvoucher_id',
                array('recipient_name', 'gift_code', 'balance', 'currency', 'status', 'expired_at',
                    'customer_check_id' => 'voucher_table.customer_id', 'recipient_email', 'customer_email'
                )
            )
            ->where('voucher_table.status <> ?', \Magestore\Giftvoucher\Model\Status::STATUS_DELETED)
            ->columns(array(
                'added_date' => new \Zend_Db_Expr("SUBDATE(added_date,INTERVAL " . $timezone . " HOUR)")))
            ->columns(array(
                'expired_at' => new \Zend_Db_Expr("SUBDATE(expired_at,INTERVAL " . $timezone . " HOUR)"),
            ));
        return $this;
    }

    /**
     * @param $customerId
     * @param $email
     */
    public function getExistedGiftcodes($customerId, $email)
    {
        $voucherTable = $this->getTable('giftvoucher');
        $this->getSelect()
            ->join(
                array('v' => $voucherTable),
                'main_table.voucher_id = v.giftvoucher_id',
                array(
                    'gift_code',
                    'balance',
                    'currency',
                    'conditions_serialized'
                )
            )->where('v.status = ?', \Magestore\Giftvoucher\Model\Status::STATUS_ACTIVE)
            ->where(
                "v.recipient_name IS NULL OR v.recipient_name = '' OR (v.customer_id <> '" .
                $customerId . "' AND v.customer_email <> ?)",
                $email
            );
    }
}
