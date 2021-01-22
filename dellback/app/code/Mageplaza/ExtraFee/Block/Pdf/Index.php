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

namespace Mageplaza\ExtraFee\Block\Pdf;

use Magento\Framework\View\Element\Template;
use Magento\Quote\Api\CartRepositoryInterface;
use Mageplaza\ExtraFee\Helper\Data;

/**
 * Class Index
 * @package Mageplaza\ExtraFee\Block\Pdf
 */
class Index extends Template
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * Index constructor.
     *
     * @param Template\Context $context
     * @param CartRepositoryInterface $cartRepository
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CartRepositoryInterface $cartRepository,
        Data $helper,
        array $data = []
    ) {
        $this->cartRepository = $cartRepository;
        $this->helper         = $helper;

        parent::__construct($context, $data);
    }

    /**
     * @param $area
     *
     * @return array
     */
    public function getExtraFee($area)
    {
        $result = [];
        $order  = $this->getOrder();
        $item   = $this->getItem();
        if (!($order && $item)) {
            return [];
        }

        $extraFee = $this->helper->getExtraFeeTotals($order);
        switch ($item->getEntityType()) {
            case 'shipment':
            case 'order':
                foreach ($extraFee as $fee) {
                    if ($fee['display_area'] === $area) {
                        $result[] = $fee;
                    }
                }
                break;
            case 'invoice':
                if ($item->getId() === $this->helper->isInvoiced($order)) {
                    foreach ($extraFee as $fee) {
                        if ($fee['display_area'] === $area) {
                            $result[] = $fee;
                        }
                    }
                }
                break;
            case 'creditmemo':
                if ($item->getId() === $this->helper->isRefunded($order)) {
                    foreach ($extraFee as $fee) {
                        if ($fee['display_area'] === $area && $fee['rf'] !== '1') {
                            $result[] = $fee;
                        }
                    }
                }
                break;
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->getData('order');
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->getData('item');
    }
}
