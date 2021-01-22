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

namespace Mageplaza\ExtraFee\Model\Api;

use Exception;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Quote\Model\Quote;
use Mageplaza\ExtraFee\Api\RuleInterface;
use Mageplaza\ExtraFee\Helper\Data as HelperData;

/**
 * Class RuleManagement
 * @package Mageplaza\ExtraFee\Model\Api
 */
class RuleManagement implements RuleInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * Cart total repository.
     *
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalsRepository;

    /**
     * SpendingManagement constructor.
     *
     * @param HelperData $helperData
     * @param CartRepositoryInterface $cartRepository
     * @param CartTotalRepositoryInterface $cartTotalsRepository
     */
    public function __construct(
        HelperData $helperData,
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalsRepository
    ) {
        $this->helperData           = $helperData;
        $this->cartRepository       = $cartRepository;
        $this->cartTotalsRepository = $cartTotalsRepository;
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     */
    public function update($cartId, $area, ShippingInformationInterface $addressInformation)
    {
        /** @var Quote $quote */
        $quote = $this->cartRepository->get($cartId);

        $quote->setBillingAddress($addressInformation->getBillingAddress());
        $quote->setShippingAddress($addressInformation->getShippingAddress());
        /** @var Session $checkoutSession */
        $checkoutSession     = $this->helperData->getCheckoutSession();
        $quote               = $checkoutSession->getQuote();
        $extensionAttributes = $addressInformation->getExtensionAttributes();
        if ($extensionAttributes) {
            $quote->getPayment()->setMethod($extensionAttributes->getMpEfPaymentMethod());
        }

        $checkoutSession->setMpArea($area);
        $quote->collectTotals();

        return $checkoutSession->getMpExtraFee() ?: [[], []];
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function collectTotal($cartId, $formData, $area)
    {
        /** @var Quote $quote */
        $quote         = $this->cartRepository->getActive($cartId);
        $areaArray     = explode(',', $area);
        $formDataArray = explode(',', $formData);

        foreach ($areaArray as $key => $item) {
            $this->helperData->setMpExtraFee($quote, $formDataArray[$key], $item);
        }

        try {
            $quote->collectTotals();
            $this->cartRepository->save($quote);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Could not add extra fee for this quote'));
        }

        return $this->getResponseData($quote);
    }

    /**
     * @param Quote $quote
     *
     * @return TotalsInterface
     * @throws NoSuchEntityException
     */
    public function getResponseData(Quote $quote)
    {
        return $this->cartTotalsRepository->get($quote->getId());
    }

    /**
     * @param Quote $quote
     *
     * @return void
     * @throws LocalizedException
     */
    protected function validateQuote(Quote $quote)
    {
        if ($quote->getItemsCount() === 0) {
            throw new LocalizedException(
                __('Totals calculation is not applicable to empty cart.')
            );
        }
    }
}
