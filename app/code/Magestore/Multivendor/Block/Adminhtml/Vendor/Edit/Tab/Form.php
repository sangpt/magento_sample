<?php
namespace Magestore\Multivendor\Block\Adminhtml\Vendor\Edit\Tab;
/**
* Class Form
* @package Magestore\Multivendor\Block\Adminhtml\Vendor\Edit\Tab
*/
class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
    * @var \Magento\Framework\ObjectManagerInterface
    */
    protected $_objectManager;

    /**
    * @param \Magento\Backend\Block\Template\Context $context
    * @param \Magento\Framework\Registry $registry
    * @param \Magento\Framework\Data\FormFactory $formFactory
    * @param \Magento\Framework\ObjectManagerInterface $objectManager
    * @param array $data
    */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = array()
    )
    {
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
    * @throws \Magento\Framework\Exception\LocalizedException
    */
    protected function _prepareLayout() {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
    }

    /**
    * @return $this
    * @throws \Magento\Framework\Exception\LocalizedException
    */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_vendor');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Vendor Information')));
        if ($model->getVendorId()) {
            $fieldset->addField('vendor_id', 'hidden', array('name' => 'vendor_id'));
        }

        $fieldset->addField('vendor_code', 'text', array(
            'label' => __('Vendor Code'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'vendor_code',
            'disabled' => false,
        ));
        $fieldset->addField('name', 'text', array(
            'label' => __('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
            'disabled' => false,
        ));
        $fieldset->addField('description', 'textarea', array(
            'label' => __('Description'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'description',
            'disabled' => false,
        ));
        $fieldset->addField('logo', 'image', array(
            'label' => __('Logo'),
            'name' => 'logo',
            'disabled' => false,
        ));
        $fieldset->addField('address', 'text', array(
            'label' => __('Address'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'address',
            'disabled' => false,
        ));
        $fieldset->addField('phone', 'text', array(
            'label' => __('Phone'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'phone',
            'disabled' => false,
        ));
        $fieldset->addField('status', 'select', array(
            'label' => __('Status'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'status',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
            'disabled' => false,
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
    * @return mixed
    */
    public function getVendor() {
        return $this->_coreRegistry->registry('current_vendor');
    }

    /**
    * @return \Magento\Framework\Phrase
    */
    public function getPageTitle() {
        return $this->getVendor()->getId() ? __("Edit Vendor %1", $this->escapeHtml($this->getVendor()->getDisplayName())) : __('New Vendor');
    }

    /**
    * @return \Magento\Framework\Phrase
    */
    public function getTabLabel()
    {
        return __('Vendor Information');
    }

    /**
    * @return \Magento\Framework\Phrase
    */
    public function getTabTitle()
    {
        return __('Vendor Information');
    }

    /**
    * @return bool
    */
    public function canShowTab()
    {
        return true;
    }

    /**
    * @return bool
    */
    public function isHidden()
    {
        return false;
    }
}
