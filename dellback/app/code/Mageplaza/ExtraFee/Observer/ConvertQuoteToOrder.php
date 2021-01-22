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

namespace Mageplaza\ExtraFee\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\ExtraFee\Helper\Data;

/**
 * Class ConvertQuoteToOrder
 * @package Mageplaza\ExtraFee\Observer
 */
class ConvertQuoteToOrder implements ObserverInterface
{
    /**
     * @var Data
     */
    private $helperData;

    /**
     * @param Data $helperData
     */
    public function __construct(Data $helperData)
    {
        $this->helperData = $helperData;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $order->setMpExtraFee($quote->getMpExtraFee());

        $billingExtraFee  = $this->helperData->getMpExtraFee($order, '1');
        $shippingExtraFee = $this->helperData->getMpExtraFee($order, '2');
        $extraFee         = $this->helperData->getMpExtraFee($order, '3');

        if (!empty($billingExtraFee)) {
            $order->setHasBillingExtraFee(true);
        }
        if (!empty($shippingExtraFee)) {
            $order->setHasShippingExtraFee(true);
        }
        if (!empty($extraFee)) {
            $order->setHasExtraFee(true);
        }

        return $this;
    }
}
