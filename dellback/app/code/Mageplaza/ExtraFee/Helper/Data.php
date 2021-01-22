<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ExtraFee
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ExtraFee\Helper;

use Magento\Backend\Model\Session\Quote;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;
use Mageplaza\ExtraFee\Model\Config\Source\DisplayArea;
use Mageplaza\ExtraFee\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class Data
 * @package Mageplaza\ExtraFee\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'mp_extra_fee';

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var CollectionFactory
     */
    protected $ruleCollectionFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param CheckoutSession $checkoutSession
     * @param CartRepositoryInterface $quoteRepository
     * @param CollectionFactory $ruleCollectionFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        CollectionFactory $ruleCollectionFactory
    ) {
        $this->checkoutSession       = $checkoutSession;
        $this->quoteRepository       = $quoteRepository;
        $this->ruleCollectionFactory = $ruleCollectionFactory;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * Collect and save total
     *
     * @param null $quote
     *
     * @return $this
     */
    public function collectTotals($quote = null)
    {
        if ($this->isAdmin()) {
            return $this;
        }

        if ($quote === null) {
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->getCheckoutSession()->getQuote();
        }

        $quote->getShippingAddress()->setCollectShippingRates(true);

        $this->quoteRepository->save($quote->collectTotals());

        return $this;
    }

    /**
     * Get checkout session for admin and frontend
     *
     * @return CheckoutSession|mixed
     */
    public function getCheckoutSession()
    {
        if (!$this->checkoutSession) {
            $this->checkoutSession = $this->objectManager
                ->get($this->isAdmin() ? Quote::class : CheckoutSession::class);
        }

        return $this->checkoutSession;
    }

    /**
     * @param $quote
     * @param bool $area
     *
     * @return array
     */
    public function getMpExtraFee($quote, $area = false)
    {
        $extraFee = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];
        switch ($area) {
            case DisplayArea::PAYMENT_METHOD:
                if (isset($extraFee['payment'])) {
                    parse_str($extraFee['payment'], $result);

                    return $result;
                }

                return [];
            case DisplayArea::SHIPPING_METHOD:
                if (isset($extraFee['shipping'])) {
                    parse_str($extraFee['shipping'], $result);

                    return $result;
                }

                return [];
            case DisplayArea::CART_SUMMARY:
                if (isset($extraFee['summary'])) {
                    parse_str($extraFee['summary'], $result);

                    return $result;
                }

                return [];
            case '4':
                if (isset($extraFee['totals'])) {
                    return $extraFee['totals'];
                }

                return [];
            default:
                $result = [];
                foreach ($extraFee as $index => $item) {
                    if (in_array($index, ['totals', 'is_invoiced', 'is_refunded'])) {
                        continue;
                    }
                    parse_str($item, $rule);
                    if (isset($rule['rule']) && is_array($rule['rule'])) {
                        foreach ($rule['rule'] as $key => $value) {
                            $result[$key] = $value;
                        }
                    }
                }

                return $result;
        }
    }

    /**
     * @param $quote
     *
     * @return array|mixed
     */
    public function getExtraFeeTotals($quote)
    {
        $extraFee = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];

        return isset($extraFee['totals']) ? $extraFee['totals'] : [];
    }

    /**
     * @param $quote
     * @param $value
     * @param $area
     */
    public function setMpExtraFee($quote, $value, $area)
    {
        $extraFee = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];

        if (!isset($extraFee['summary'])) {
            $ruleCollection = $this->ruleCollectionFactory->create()->addFieldToFilter('area', '3');
            $defaults       = [];
            foreach ($ruleCollection as $rule) {
                $default = self::jsonDecode($rule->getOptions())['default'];
                if ($default) {
                    $defaults[$rule->getId()] = $default[0];
                }
            }
            $extraFee['summary'] = http_build_query(['rule' => $defaults]);
        }

        switch ($area) {
            case DisplayArea::PAYMENT_METHOD:
                $extraFee['payment'] = $value;
                break;
            case DisplayArea::SHIPPING_METHOD:
                $extraFee['shipping'] = $value;
                break;
            case DisplayArea::CART_SUMMARY:
                $extraFee['summary'] = $value;
                break;
            case '4':
                $extraFee['totals'] = $value;
        }

        $quote->setMpExtraFee($this::jsonEncode($extraFee))->save();
    }

    /**
     * @param $rule
     * @param $storeId
     *
     * @return string
     */
    public function getRuleLabel($rule, $storeId)
    {
        $labels = $rule->getlabels() ? $this::jsonDecode($rule->getlabels()) : [];

        return isset($labels[$storeId]) ? ($labels[$storeId] ?: $rule->getName()) : '';
    }

    /**
     * @param $quote
     * @param $invoiceId
     */
    public function setInvoiced($quote, $invoiceId)
    {
        $extraFee                = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];
        $extraFee['is_invoiced'] = $invoiceId;
        $quote->setMpExtraFee($this::jsonEncode($extraFee))->save();
    }

    /**
     * @param $quote
     *
     * @return bool|mixed
     */
    public function isInvoiced($quote)
    {
        $extraFee = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];

        return isset($extraFee['is_invoiced']) ? $extraFee['is_invoiced'] : false;
    }

    /**
     * @param Quote|Order $quote
     * @param $creditmemoId
     */
    public function setRefunded($quote, $creditmemoId)
    {
        $extraFee                = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];
        $extraFee['is_refunded'] = $creditmemoId;
        $quote->setMpExtraFee($this::jsonEncode($extraFee))->save();
    }

    /**
     * @param $quote
     *
     * @return bool|mixed
     */
    public function isRefunded($quote)
    {
        $extraFee = $quote->getMpExtraFee() ? $this::jsonDecode($quote->getMpExtraFee()) : [];

        return isset($extraFee['is_refunded']) ? $extraFee['is_refunded'] : false;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->isEnabled();
    }

    /**
     * @return bool
     */
    public function isOscPage()
    {
        $moduleEnable = $this->isModuleOutputEnabled('Mageplaza_Osc');
        $isOscModule  = ($this->_request->getRouteName() === 'onestepcheckout');

        return $moduleEnable && $isOscModule && $this->isEnabled();
    }
}
