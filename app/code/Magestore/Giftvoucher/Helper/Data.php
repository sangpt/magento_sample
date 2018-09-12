<?php
/**
 * Copyright © 2017 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Giftvoucher\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Tax\Model\Config;
use Magento\Store\Model\Store;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Giftvoucher default helper
 *
 * @category Magestore
 * @package  Magestore_Giftvoucher
 * @module   Giftvoucher
 * @author   Magestore Developer
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_moduleList;

    protected $_imageUrl;
    protected $_imageName;
    protected $_imageReturn;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    
    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;
    
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;
    
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;
    
    /**
     * @var \Magento\Eav\Model\Entity\Attribute\Set
     */
    protected $_attributeSet;
    
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $_inlineTranslation;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currencyModel;
    
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_locale;
    
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;
    
    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_imageFactory;
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    
    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;
    
    /**
     * @var \Magento\Tax\Model\Calculation
     */
    protected $_taxCalculation;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;
    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;
    /**
     * @var
     */
    protected $view;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\Eav\Model\Entity\Attribute\Set $attributeSet
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\Currency $currencyModel
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param CustomerSession $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Tax\Model\Calculation $taxCalculation
     * @param \Magento\Backend\Model\Session\Quote $sessionQuote
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Framework\View\Element\Template $view
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyFactory,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Eav\Model\Entity\Attribute\Set $attributeSet,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currencyModel,
        \Magento\Framework\Locale\ResolverInterface $locale,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\View\Element\Template $view,
        ModuleListInterface $moduleList
    ) {
        $this->_objectManager = $objectManager;
        $this->_priceCurrency = $priceCurrency;
        $this->_productFactory = $productFactory;
        $this->_product = $product;
        $this->_customer = $customer;
        $this->_attributeSet= $attributeSet;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->_currencyModel = $currencyModel;
        $this->_locale = $locale;
        $this->_filesystem = $filesystem;
        $this->_imageFactory = $imageFactory;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_appState = $appState;
        $this->messageManager = $messageManager;
        $this->_taxCalculation = $taxCalculation;
        $this->_sessionQuote = $sessionQuote;
        $this->_localeDate = $localeDate;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->view = $view;
        $this->_moduleList = $moduleList;
        parent::__construct($context);
    }
    
    /**
     * Get Gift Card general configuration
     *
     * @param string $code
     * @param int|null $store
     * @return boolean
     */
    public function getGeneralConfig($code, $store = null)
    {
        if ($code == 'barcode_enable' || $code == 'barcode_type' || $code == 'logo') {
            return $this->scopeConfig->getValue('giftvoucher/print_voucher/' . $code, 'store', $store);
        }
        return $this->scopeConfig->getValue('giftvoucher/general/' . $code, 'store', $store);
    }
    
    /**
     * Get Gift Card print-out configuration
     *
     * @param string $code
     * @param int|null $store
     * @return boolean
     */
    public function getPrintConfig($code, $store = null)
    {
        return $this->scopeConfig->getValue('giftvoucher/print_voucher/' . $code, 'store', $store);
    }
    
    
    
    /**
     * Get Gift Card interface configuration
     *
     * @param string $code
     * @param int|null $store
     * @return boolean
     */
    public function getInterfaceConfig($code, $store = null)
    {
        return $this->scopeConfig->getValue('giftvoucher/interface/' . $code, 'store', $store);
    }

    /**
     * Get Gift Card checkout configuration
     *
     * @param string $code
     * @param int|null $store
     * @return boolean
     */
    public function getInterfaceCheckoutConfig($code, $store = null)
    {
        return $this->scopeConfig->getValue('giftvoucher/interface_checkout/' . $code, 'store', $store);
    }

    /**
     * Get Gift Card email configuration
     *
     * @param $code
     * @param int|null $store
     * @return bool
     */
    public function getEmailConfig($code, $store = null)
    {
        return $this->scopeConfig->getValue('giftvoucher/email/' . $code, 'store', $store);
    }

    /**
     * @param $code
     * @param null $store
     * @return mixed
     */
    public function getStoreConfig($code, $store = null)
    {
        return $this->scopeConfig->getValue($code, 'store', $store);
    }

    /**
     * @return \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    public function getBaseDirMedia()
    {
        return $this->_filesystem->getDirectoryRead('media');
    }

    /**
     * @return \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    public function getBaseDir()
    {
        return $this->_filesystem->getDirectoryRead();
    }

    /**
     * @return CustomerSession
     */
    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * Returns a gift code string
     *
     * @param $expression
     * @return string
     * @internal param string $param
     */
    public function calcCode($expression)
    {
        if ($this->isExpression($expression)) {
            return preg_replace_callback('#\[([AN]{1,2})\.([0-9]+)\]#', array($this, 'convertExpression'), $expression);
        } else {
            return $expression;
        }
    }

    /**
     * Convert a expression to the numeric and alphabet
     *
     * @param string $param
     * @return string
     */
    public function convertExpression($param)
    {
        $alphabet = (strpos($param[1], 'A')) === false ? '' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphabet .= (strpos($param[1], 'N')) === false ? '' : '0123456789';
        return $this->_objectManager->create('Magento\Framework\Math\Random')->getRandomString($param[2], $alphabet);
    }

    /**
     * Check a string whether it is a expression or not
     *
     * @param string $string
     * @return int|boolean
     */
    public function isExpression($string)
    {
        return preg_match('#\[([AN]{1,2})\.([0-9]+)\]#', $string);
    }

    /**
     * Get Gift Card product options configuration
     *
     * @return array
     */
    public function getGiftVoucherOptions()
    {
        $option = explode(',', $this->getInterfaceCheckoutConfig('display'));
        $result = array();
        foreach ($option as $key => $val) {
            if ($val == 'amount') {
                $result['amount'] = __('Gift Card value');
            }
            if ($val == 'giftcard_template_id') {
                $result['giftcard_template_id'] = __('Gift Card template');
            }
            if ($val == 'customer_name') {
                $result['customer_name'] = __('Sender name');
            }
            if ($val == 'recipient_name') {
                $result['recipient_name'] = __('Recipient name');
            }
            if ($val == 'recipient_email') {
                $result['recipient_email'] = __('Recipient email address');
            }
            if ($val == 'recipient_ship') {
                $result['recipient_ship'] = __('Ship to recipient');
            }
            if ($val == 'recipient_address') {
                $result['recipient_address'] = __('Recipient address');
            }
            if ($val == 'message') {
                $result['message'] = __('Custom message');
            }
            if ($val == 'day_to_send') {
                $result['day_to_send'] = __('Day to send');
            }
            if ($val == 'timezone_to_send') {
                $result['timezone_to_send'] = __('Time zone');
            }
            if ($val == 'giftcard_use_custom_image') {
                $result['giftcard_use_custom_image'] = __('Use custom image');
            }
        }
        return $result;
    }

    /**
     * Get the full Gift Card options
     *
     * @return array
     */
    public function getFullGiftVoucherOptions()
    {
        return array(
            'customer_name' => __('Sender Name'),
            'giftcard_template_id' => __('Giftcard Template'),
            'send_friend' => __('Send Gift Card to friend'),
            'recipient_name' => __('Recipient name'),
            'recipient_email' => __('Recipient email'),
            'recipient_ship' => __('Ship to recipient'),
            'recipient_address' => __('Recipient address'),
            'message' => __('Custom message'),
            'day_to_send' => __('Day To Send'),
            'timezone_to_send' => __('Time zone'),
            'email_sender' => __('Email To Sender'),
            'amount' => __('Amount'),
            'giftcard_template_image' => __('Giftcard Image'),
            'giftcard_use_custom_image' => __('Use Custom Image'),
            'notify_success' => __('Notify when the recipient receives Gift Card.')
        );
    }

    /**
     * Get the hidden gift code
     *
     * @param string $code
     * @return string
     */
    public function getHiddenCode($code)
    {
        
        $prefix = $this->getGeneralConfig('showprefix');
        $prefixCode = substr($code, 0, $prefix);
        $suffixCode = substr($code, $prefix);
        if ($suffixCode) {
            $hiddenChar = $this->getGeneralConfig('hiddenchar');
            if (!$hiddenChar) {
                $hiddenChar = 'X';
            } else {
                $hiddenChar = substr($hiddenChar, 0, 1);
            }
            $suffixCode = preg_replace('#([a-zA-Z0-9\-])#', $hiddenChar, $suffixCode);
        }
        return $prefixCode . $suffixCode;
    }

    /**
     * Check gift codes whether they are available to add or not
     *
     * @return boolean
     */
    public function isAvailableToAddCode()
    {
        $codes = $this->_objectManager->get('Magestore\Giftvoucher\Model\Session')->getCodes();
        if ($max = $this->getGeneralConfig('maximum')) {
            if (count($codes) >= $max) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isAvailableToCheckCode()
    {
        $codes = $this->_objectManager->get('Magestore\Giftvoucher\Model\Session')->getCodesInvalid();
        if ($max = $this->getGeneralConfig('maximum')) {
            if (count($codes) >= $max) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check code can used to checkout or not
     *
     * @param mixed $code
     * @return boolean
     */
    public function canUseCode($code)
    {
        if (!$code) {
            return false;
        }
        if (is_string($code)) {
            $code = $this->_objectManager->create('Magestore\Giftvoucher\Model\Giftvoucher')->loadByCode($code);
        }
        if (!($code instanceof \Magestore\Giftvoucher\Model\Giftvoucher)) {
            return false;
        }
        if (!$code->getId()) {
            return false;
        }
        if ($this->_appState->getAreaCode()=='adminhtml') {
            $customerId = $this->_sessionQuote->getCustomerId();
        } else {
            $customerId = $this->getCustomerSession()->getCustomerId();
        }
        $shareCard = intval($this->getGeneralConfig('share_card'));
        if ($shareCard < 1) {
            return true;
        }
        $customersUsed = $code->getCustomerIdsUsed();
        if ($shareCard > count($customersUsed) || in_array($customerId, $customersUsed)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get store id from quote (fix Magento create order in backend)
     * 
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return int
     */
    public function getStoreId($quote)
    {
        if ($this->_appState->getAreaCode() == 'adminhtml') {
            return $this->_sessionQuote->getStoreId();
        }
        return $quote->getStoreId();
    }

    /**
     * @return array
     */
    public function getAllowedCurrencies()
    {
        $baseCode = $this->_storeManager->getStore()->getBaseCurrencyCode();
        $allowedCurrencies = $this->_currencyModel->getConfigAllowCurrencies();
        $currencies = $this->_objectManager->create('Magento\Framework\Locale\Bundle\CurrencyBundle')
            ->get($this->_locale->getLocale())['Currencies'];
        $options = [];
        foreach ($currencies as $code => $data) {
            if (!in_array($code, $allowedCurrencies)) {
                continue;
            }
            $options[] = ['label' => $data[1], 'value' => $code];
        }

        return $options;
    }


    /**
     * upload template image
     * @param type $type
     * @return type
     */
    public function uploadImage($type)
    {
        $image = '';
        try {
            $files = $this->_getRequest()->getFiles($type);
            if (isset($files['error']) && $files['error'] == 0) {
                $uploader = $this->_objectManager->create('Magento\Framework\File\Uploader', array('fileId' => $type));
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->_filesystem->getDirectoryRead('media');

                $result = $uploader->save($mediaDirectory->getAbsolutePath('giftvoucher/template/images'));
                unset($result['tmp_name']);
                unset($result['path']);
                $image = $result['file'];
                $this->resizeImage($image);
                $this->customResizeImage($image, 'images');
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $image;
    }

    /**
     * upload template image
     * @return type
     * @internal param type $type
     */
    public function uploadTemplateBackground()
    {
        $image = '';
        try {
            $files = $this->_getRequest()->getFiles('background_img');
            if (isset($files['error']) && $files['error'] == 0) {
                $uploader = $this->_objectManager->create(
                    'Magento\Framework\File\Uploader',
                    array('fileId' => 'background_img')
                );
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                $result = $uploader->save($mediaDirectory->getAbsolutePath('giftvoucher/template/background'));
                unset($result['tmp_name']);
                unset($result['path']);
                $image = $result['file'];
                $this->resizeImage($image, 'background');
                $this->customResizeImage($image, 'background');
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $image;
    }
    
    /**
     * delete image
     * @param type $image
     * @return type
     */
    public function deleteImageFile($image)
    {
        if (!$image) {
            return;
        }
        try {
            unset($image);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create folder for the Gift Card product image
     * @param $parent
     * @param $type
     * @param bool $tmp
     */
    public function createImageFolderHaitv($parent, $type, $tmp = false)
    {
        if ($type !== '') {
            $urlType = $type . '/';
        } else {
            $urlType = '';
        }
        if ($tmp) {
            $imagePath = $this->getBaseDirMedia()->getAbsolutePath('tmp/giftvoucher/images/');
        } else {
            $imagePath = $this->getBaseDirMedia()->getAbsolutePath('giftvoucher/template/'. $parent . '/' . $urlType);
        }
        if (!is_dir($imagePath)) {
            try {
                mkdir($imagePath);
                chmod($imagePath, 0777);
            } catch (\Exception $e) {
            }
        }
    }

    /*
     * resize image when admin upload template image
     * @param string $imageName
     * @return null
     */
    /**
     * @param $imageName
     * @param string $type
     */
    public function resizeImage($imageName, $type = 'images')
    {
        if ($imageName) {
            $imageDir = $this->getBaseDirMedia()->getAbsolutePath('giftvoucher/template/'.$type.'/' . $imageName);
            $resizeBarcodeObj = $this->_imageFactory->create();
            $resizeBarcodeObj->open($imageDir);
            $resizeBarcodeObj->getImage();
            $resizeBarcodeObj->constrainOnly(true);
            $resizeBarcodeObj->keepAspectRatio(false);
            $resizeBarcodeObj->keepFrame(false);
            $resizeBarcodeObj->resize(600, 365);
            $resizeBarcodeObj->save();
        }
    }

    /**
     * @param $width
     * @param null $height
     * @return $this
     */
    public function resize($width, $height = null)
    {
        $imageReturn = $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . "tmp/giftvoucher/cache/" . $this->_imageName;
        $this->_imageReturn = $imageReturn;
        if (file_exists($this->getBaseDirMedia()->getAbsolutePath(strstr($imageReturn, '/media')))) {
            //return $this;
        }
        if ($height == null) {
            $height = $width;
        }
        $imageUrl = $this->_filesystem->getDirectoryRead('media')
            ->getAbsolutePath('tmp/giftvoucher/cache'. $this->_imageName);
        $imageObj = $this->_imageFactory->create();
        $imageObj->open($this->_imageUrl);
        $imageObj->constrainOnly(true);
        $imageObj->keepAspectRatio(true);
        $imageObj->keepFrame(true);
        $imageObj->backgroundColor(array(255, 255, 255));
        $imageObj->resize($width, $height);
        try {
            $imageObj->save($imageUrl);
        } catch (\Exception $e) {
        }
        return $this;
    }

    /**
     * @param $imageName
     * @param $imageType
     */
    public function customResizeImage($imageName, $imageType)
    {
        //$imagePath = Mage::getBaseDir() . str_replace("/", DS, strstr($imagePath, '/media'));
        $imagePath = $this->getBaseDirMedia()->getAbsolutePath('giftvoucher/template/'.$imageType.'/');
        $imageUrl = $imagePath . $imageName;
        if (file_exists($imageUrl)) {
            //self::createImageFolderHaitv($imageType, 'left');
            //self::createImageFolderHaitv($imageType, 'top');
            if ($imageType == 'images') {
                $imageObj = $this->_imageFactory->create();
                $imageObj->open($imageUrl);
                $imageObj->getImage();
                $imageObj->constrainOnly(true);
                $imageObj->keepAspectRatio(false);
                $imageObj->keepFrame(false);
                $imageObj->resize(600, 190);
                $imageObj->save($imagePath . 'top/' . $imageName);

                $imageObj->resize(250, 365);
                $imageObj->save($imagePath . 'left/' . $imageName);
            } else {
                $imageObj = $this->_imageFactory->create();
                $imageObj->open($imageUrl);
                $imageObj->getImage();
                $imageObj->constrainOnly(true);
                $imageObj->keepAspectRatio(false);
                $imageObj->keepFrame(false);
                $imageObj->resize(600, 175);
                $imageObj->save($imagePath . 'top/' . $imageName);

                $imageObj->resize(350, 365);
                $imageObj->save($imagePath . 'left/' . $imageName);
            }
        }
    }

    /**
     * @param $url
     * @param $filename
     * @param $urlImage
     * @return $this
     */
    public function getProductThumbnail($url, $filename, $urlImage)
    {
        $this->_imageUrl = null;
        $this->_imageName = null;
        $this->_imageReturn = null;

        $this->_imageUrl = $url;
        $this->_imageName = $filename;
        $this->_imageReturn = $this->getStoreManager()->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                . $urlImage;
        return $this;
    }

    /**
     * Get the rate of items on quote
     *
     * @param Mage_Sales_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return float
     */
    public function getItemRateOnQuote($product, $store)
    {
        //Calculate rate to subtract taxable amount
        $priceIncludesTax =
            (bool) $this->getStoreConfig(\Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX, $store);
        $taxClassId = $product->getTaxClassId();
        if ($taxClassId && $priceIncludesTax) {
            $request = $this->_taxCalculation->getRateRequest(false, false, false, $store);
            $rate = $this->_taxCalculation->getRate($request->setProductClassId($taxClassId));
            return $rate;
        }
        return 0;
    }

    /**
     * @return mixed
     */
    public function getCheckoutHelper()
    {
        return $this->_objectManager->get('Magento\Checkout\Helper\Data');
    }

    /**
     * @return \Magento\Framework\ObjectManagerInterface
     */
    public function getObjectManager()
    {
        return $this->_objectManager;
    }

    /**
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->_storeManager;
    }

    /**
     * get rate from a currency with current currency
     * @param type $currencyFrom
     * @param null $currencyTo
     * @return type
     */
    public function getRateToCurrentCurrency($currencyFrom, $currencyTo = null)
    {
        $baseCurrency = $this->getStoreManager()->getStore()->getBaseCurrency();
        if (is_null($currencyTo)) {
            $currencyTo = $this->getStoreManager()->getStore()->getCurrentCurrency();
        }
        $rateCurrencyFrom = $baseCurrency->getRate($currencyFrom);
        $rateCurrencyTo = $baseCurrency->getRate($currencyTo);
        if ($rateCurrencyFrom) {
            return (float)$rateCurrencyTo/$rateCurrencyFrom;
        } else {
            return $rateCurrencyTo;
        }
    }
    /**
     * get currency format from other currency
     * @param type $currencyFrom
     * @param type $amount
     * @return type
     */
    public function getCurrencyFormat($amount, $currencyFrom)
    {
        $currentCurrency = $this->getStoreManager()->getStore()->getCurrentCurrency();
        if (is_string($currencyFrom)) {
            if ($currencyFrom == $currentCurrency->getCode()) {
                return $currentCurrency->format($amount);
            }
            $currencyFrom = $this->getObjectManager()->create('Magento\Directory\Model\Currency')
                ->load($currencyFrom);
        } elseif (is_null($currencyFrom)) {
            return $currentCurrency->format($amount);
        }
       
        $rate = $this->getRateToCurrentCurrency($currencyFrom);
        return $currentCurrency->format($amount * $rate);
    }

    /**
     * Get Type Of Gift Code
     *
     * @param $code
     * @return int
     */
    public function getSetIdOfCode($code)
    {
        $codes = $this->_objectManager->create('Magestore\Giftvoucher\Model\Giftvoucher')->loadByCode($code);
        return $codes->getSetId();
    }

    /**
     *
     * @param string $data
     * @param string $format
     * @return string
     */
    public function formatDate($data, $format = '')
    {
        $format = ($format == '')?'M d,Y H:i:s a':$format;
        return $this->_localeDate->date(new \DateTime($data))->format($format);
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->_moduleList
            ->getOne($this->_getModuleName())['setup_version'];
    }

    /**
     * @return bool
     */
    public function isRebuildVersion(){
        return (version_compare($this->getVersion(), '2.0.0', '<'))?false:true;
    }
}
