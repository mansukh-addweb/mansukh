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
use Magento\Sales\Model\Order\Invoice\Item;
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
 * Class Invoice
 * @package Mageplaza\ExtraFee\Model\Sales\Order\Pdf
 */
class Invoice extends \Magento\Sales\Model\Order\Pdf\Invoice
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Invoice constructor.
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
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
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
        $this->helper = $helper;

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
     * @param array $invoices
     *
     * @return Zend_Pdf
     * @throws Zend_Pdf_Exception
     */
    public function getPdf($invoices = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            /** @var \Magento\Sales\Model\Order\Invoice $invoice */
            if ($invoice->getStoreId()) {
                $this->_localeResolver->emulate($invoice->getStoreId());
                $this->_storeManager->setCurrentStore($invoice->getStoreId());
            }
            $page  = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );
            /* Add document text and number */
            $this->insertDocumentNumber($page, __('Invoice # ') . $invoice->getIncrementId());

            /* Add custom field*/
            if ($this->helper->isEnabled()) {
                $this->insertMpExtraFee($page, $order, $invoice);
            }

            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item) {
                /** @var Item $item */
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                $this->_localeResolver->revert();
            }
        }
        $this->_afterGetPdf();

        return $pdf;
    }

    /**
     * @param Zend_Pdf_Page $page
     * @param $order
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     *
     * @throws Zend_Pdf_Exception
     */
    public function insertMpExtraFee(&$page, $order, $invoice)
    {
        $extraFeeTotal = $this->helper->getExtraFeeTotals($order);

        if (!count($extraFeeTotal) || $this->helper->isInvoiced($order) !== $invoice->getId()) {
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
