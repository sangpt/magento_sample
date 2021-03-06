<?php
/**
 * Copyright © 2017 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Giftvoucher\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magestore\Giftvoucher\Model\Status as StatusModel;

/**
 * Class Status
 */
class Status extends Column
{
    /**
     * @var string[]
     */
    protected $statuses;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StatusModel $statusModel
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StatusModel $statusModel,
        array $components = [],
        array $data = []
    ) {
        $this->statuses = $statusModel->getOptionArray();
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return void
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = isset($this->statuses[$item[$this->getData('name')]])
                    ? $this->statuses[$item[$this->getData('name')]]
                    : $item[$this->getData('name')];
            }
        }

        return $dataSource;
    }
}
