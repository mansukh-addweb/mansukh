<?php
namespace Magento\Catalog\Helper\Data;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Data
 */
class Interceptor extends \Magento\Catalog\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Session $catalogSession, \Magento\Framework\Stdlib\StringUtils $string, \Magento\Catalog\Helper\Category $catalogCategory, \Magento\Catalog\Helper\Product $catalogProduct, \Magento\Framework\Registry $coreRegistry, \Magento\Catalog\Model\Template\Filter\Factory $templateFilterFactory, $templateFilterModel, \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyFactory, \Magento\Tax\Model\Config $taxConfig, \Magento\Tax\Api\Data\QuoteDetailsInterfaceFactory $quoteDetailsFactory, \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $quoteDetailsItemFactory, \Magento\Tax\Api\TaxCalculationInterface $taxCalculationService, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Customer\Api\GroupRepositoryInterface $customerGroupRepository, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory, \Magento\Customer\Api\Data\RegionInterfaceFactory $regionFactory)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $catalogSession, $string, $catalogCategory, $catalogProduct, $coreRegistry, $templateFilterFactory, $templateFilterModel, $taxClassKeyFactory, $taxConfig, $quoteDetailsFactory, $quoteDetailsItemFactory, $taxCalculationService, $customerSession, $priceCurrency, $productRepository, $categoryRepository, $customerGroupRepository, $addressFactory, $regionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreId');
        if (!$pluginInfo) {
            return parent::setStoreId($store);
        } else {
            return $this->___callPlugins('setStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBreadcrumbPath()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBreadcrumbPath');
        if (!$pluginInfo) {
            return parent::getBreadcrumbPath();
        } else {
            return $this->___callPlugins('getBreadcrumbPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCategory');
        if (!$pluginInfo) {
            return parent::getCategory();
        } else {
            return $this->___callPlugins('getCategory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProduct');
        if (!$pluginInfo) {
            return parent::getProduct();
        } else {
            return $this->___callPlugins('getProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLastViewedUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLastViewedUrl');
        if (!$pluginInfo) {
            return parent::getLastViewedUrl();
        } else {
            return $this->___callPlugins('getLastViewedUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function splitSku($sku, $length = 30)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'splitSku');
        if (!$pluginInfo) {
            return parent::splitSku($sku, $length);
        } else {
            return $this->___callPlugins('splitSku', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeHiddenFields()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributeHiddenFields');
        if (!$pluginInfo) {
            return parent::getAttributeHiddenFields();
        } else {
            return $this->___callPlugins('getAttributeHiddenFields', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceScope() : ?int
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPriceScope');
        if (!$pluginInfo) {
            return parent::getPriceScope();
        } else {
            return $this->___callPlugins('getPriceScope', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPriceGlobal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPriceGlobal');
        if (!$pluginInfo) {
            return parent::isPriceGlobal();
        } else {
            return $this->___callPlugins('isPriceGlobal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isUsingStaticUrlsAllowed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isUsingStaticUrlsAllowed');
        if (!$pluginInfo) {
            return parent::isUsingStaticUrlsAllowed();
        } else {
            return $this->___callPlugins('isUsingStaticUrlsAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isUrlDirectivesParsingAllowed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isUrlDirectivesParsingAllowed');
        if (!$pluginInfo) {
            return parent::isUrlDirectivesParsingAllowed();
        } else {
            return $this->___callPlugins('isUrlDirectivesParsingAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPageTemplateProcessor()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPageTemplateProcessor');
        if (!$pluginInfo) {
            return parent::getPageTemplateProcessor();
        } else {
            return $this->___callPlugins('getPageTemplateProcessor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function shouldDisplayProductCountOnLayer($storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'shouldDisplayProductCountOnLayer');
        if (!$pluginInfo) {
            return parent::shouldDisplayProductCountOnLayer($storeId);
        } else {
            return $this->___callPlugins('shouldDisplayProductCountOnLayer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxPrice($product, $price, $includingTax = null, $shippingAddress = null, $billingAddress = null, $ctc = null, $store = null, $priceIncludesTax = null, $roundPrice = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxPrice');
        if (!$pluginInfo) {
            return parent::getTaxPrice($product, $price, $includingTax, $shippingAddress, $billingAddress, $ctc, $store, $priceIncludesTax, $roundPrice);
        } else {
            return $this->___callPlugins('getTaxPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
