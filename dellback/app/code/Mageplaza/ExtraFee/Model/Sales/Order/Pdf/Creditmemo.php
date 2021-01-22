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

namespace Mageplaza\ExtraFee\Model\Sales\Order\Pdf;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Payment\Helper\Data as PaymentData;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\ItemsFactory;
use Magento\Sales\Model\Order\Pdf\Total;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\ExtraFee\Helper\Data;
use Zend_Pdf;
use Zend_Pdf_Color_GrayScale;
use Zend_Pdf_Color_Rgb;
use Zend_Pdf_Exception;
use Zend_Pdf_Page;
use Zend_Pdf_Style;

/**
 * Class Creditmemo
 * @package Mageplaza\ExtraFee\Model\Sales\Order\Pdf
 */
class Creditmemo extends \Magento\Sales\Model\Order\Pdf\Creditmemo
{
    /**
     * @var
     */
    protected $_localeResolver;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * Creditmemo constructor.
     *
     * @param PaymentData $paymentData
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param Config $pdfConfig
     * @param Total\Factory $pdfTotalFactory
     * @param ItemsFactory $pdfItemsFactory
     * @param TimezoneInterface $localeDate
     * @param StateInterface $inlineTranslation
     * @param Renderer $addressRenderer
     * @param StoreManagerInterface $storeManager
     * @param ResolverInterface $localeResolver
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        PaymentData $paymentData,
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        Config $pdfConfig,
        Total\Factory $pdfTotalFactory,
        ItemsFactory $pdfItemsFactory,
        TimezoneInterface $localeDate,
        StateInterface $inlineTranslation,
        Renderer $addressRenderer,
        StoreManagerInterface $storeManager,
        ResolverInterface $localeResolver,
        Data $helper,
        array $data = []
    ) {
        $this->_localeResolver = $localeResolver;
        $this->helper          = $helper;

        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $storeManager,
            $localeResolver,
            $data
        );
    }

    /**
     * @param array $creditmemos
     *
     * @return Zend_Pdf
     * @throws Zend_Pdf_Exception
     */
    public function getPdf($creditmemos = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('creditmemo');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($creditmemos as $creditmemo) {
            /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
            if ($creditmemo->getStoreId()) {
                $this->_localeResolver->emulate($creditmemo->getStoreId());
                $this->_storeManager->setCurrentStore($creditmemo->getStoreId());
            }
            $page  = $this->newPage();
            $order = $creditmemo->getOrder();
            /* Add image */
            $this->insertLogo($page, $creditmemo->getStore());
            /* Add address */
            $this->insertAddress($page, $creditmemo->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_CREDITMEMO_PUT_ORDER_ID,
                    ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );

            if ($this->helper->isEnabled()) {
                $this->insertMpExtraFee($page, $order, $creditmemo);
            }

            /* Add document text and number */
            $this->insertDocumentNumber($page, __('Credit Memo # ') . $creditmemo->getIncrementId());
            /* Add table head */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($creditmemo->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $creditmemo);
        }
        $this->_afterGetPdf();
        if ($creditmemo->getStoreId()) {
            $this->_localeResolver->revert();
        }

        return $pdf;
    }

    /**
     * @param Zend_Pdf_Page $page
     * @param $order
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     *
     * @throws Zend_Pdf_Exception
     */
    public function insertMpExtraFee(&$page, $order, $creditmemo)
    {
        $extraFeeTotal = $this->helper->getExtraFeeTotals($order);

        if (!count($extraFeeTotal) || $this->helper->isRefunded($order) !== $creditmemo->getId()) {
            return;
        }

        $this->y += 8;
        $page->setFillColor(new Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 25);
        $this->_setFontBold($page, 12);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $page->drawText(__('Extra Fee Information'), 35, $this->y - 17, 'UTF-8');

        $this->y -= 25;

        $infoYTop = $this->y;

        $paymentExtraFee  = [];
        $shippingExtraFee = [];
        $extraFee         = [];
        foreach ($extraFeeTotal as $fee) {
            switch ($fee['display_area']) {
                case '1':
                    $paymentExtraFee[] = $fee;
                    break;
                case '2':
                    $shippingExtraFee[] = $fee;
                    break;
                default:
                    $extraFee[] = $fee;
            }
        }

        $this->_setFontBold($page, 10);
        $page->drawText(__('Payment Extra Fee'), 35, $this->y - 17, 'UTF-8');
        $page->drawText(__('Shipping Extra Fee'), 290, $this->y - 17, 'UTF-8');
        $shipmentExtraFeeHeight = 34;
        $this->_setFontRegular($page, 9);

        $paymentExtraFeeHeight = 34;
        foreach ($paymentExtraFee as $item) {
            $page->drawText(
                $item['rule_label'] . ($item['label'] ? " - {$item['label']}" : ''),
                35,
                $this->y - $paymentExtraFeeHeight,
                'UTF-8'
            );
            $paymentExtraFeeHeight += 17;
        }

        foreach ($shippingExtraFee as $item) {
            $page->drawText(
                $item['rule_label'] . ($item['label'] ? " - {$item['label']}" : ''),
                290,
                $this->y - $shipmentExtraFeeHeight,
                'UTF-8'
            );
            $shipmentExtraFeeHeight += 17;
        }

        $this->y -= max($shipmentExtraFeeHeight, $paymentExtraFeeHeight);

        $this->_setFontBold($page, 10);
        $page->drawText(__('Extra Fee'), 35, $this->y - 5, 'UTF-8');

        $this->_setFontRegular($page, 9);

        $extraFeeHeight = 5 + 17;
        foreach ($extraFee as $item) {
            $page->drawText(
                $item['rule_label'] . ($item['label'] ? " - {$item['label']}" : ''),
                35,
                $this->y - $extraFeeHeight,
                'UTF-8'
            );
            $extraFeeHeight += 17;
        }

        $this->y -= $extraFeeHeight;

        $page->drawLine(25, $this->y, 25, $infoYTop);
        $page->drawLine(570, $this->y, 570, $infoYTop);
        $page->drawLine(25, $this->y, 570, $this->y);

        $this->y -= 15;
    }
}
