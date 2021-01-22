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

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Cart;
use Mageplaza\ExtraFee\Helper\Data;

/**
 * Class PaypalPrepareItems
 * @package Mageplaza\ExtraFee\Observer
 */
class PaypalPrepareItems implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * PaypalPrepareItems constructor.
     *
     * @param Session $checkoutSession
     * @param Data $helperData
     */
    public function __construct(Session $checkoutSession, Data $helperData)
    {
        $this->checkoutSession = $checkoutSession;
        $this->helperData      = $helperData;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Cart $cart */
        $cart      = $observer->getEvent()->getCart();
        $extraFees = $this->helperData->getExtraFeeTotals($this->checkoutSession->getQuote());

        if (!empty($extraFees)) {
            $total = 0;
            $qty   = 0;
            foreach ($extraFees as $extraFee) {
                if (is_array($extraFee)) {
                    $qty++;
                    $total += $extraFee['value_incl_tax'];
                }
            }
            if ($qty > 0) {
                $cart->addCustomItem(__('Extra Fee'), 1, $total);
            }
        }
    }
}
