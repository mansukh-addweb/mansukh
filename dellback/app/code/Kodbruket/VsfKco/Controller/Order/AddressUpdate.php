<?php
namespace Kodbruket\VsfKco\Controller\Order;

use Klarna\Core\Api\OrderRepositoryInterface;
use Klarna\Core\Model\OrderFactory;
use Kodbruket\VsfKco\Model\ExtensionConstants;
use Kodbruket\VsfKco\Model\Klarna\DataTransform\Request\Address;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteRepository as MageQuoteRepository;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class AddressUpdate extends Action implements CsrfAwareActionInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var MageQuoteRepository
     */
    private $mageQuoteRepository;

    /**
     * @var DataObject
     */
    private $klarnaRequestData;

    /**
     * @var Address
     */
    private $addressDataTransform;

    /**
     * @var OrderFactory
     */
    private $klarnaOrderFactory;

    /**
     * @var OrderRepositoryInterface
     */
    private $klarnaOrderRepository;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Validate constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param CartRepositoryInterface $cartRepository
     * @param MageQuoteRepository $mageQuoteRepository
     * @param Address $addressDataTransform
     * @param OrderFactory $klarnaOrderFactory
     * @param CustomerFactory $customerFactory
     * @param OrderRepositoryInterface $klarnaOrderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        CartRepositoryInterface $cartRepository,
        MageQuoteRepository $mageQuoteRepository,
        Address $addressDataTransform,
        OrderFactory $klarnaOrderFactory,
        CustomerFactory $customerFactory,
        OrderRepositoryInterface $klarnaOrderRepository,
        CustomerRepositoryInterface $customerRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct(
            $context
        );
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
        $this->mageQuoteRepository = $mageQuoteRepository;
        $this->addressDataTransform = $addressDataTransform;
        $this->klarnaOrderFactory = $klarnaOrderFactory;
        $this->klarnaOrderRepository = $klarnaOrderRepository;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $data = $this->getKlarnaRequestData();
        $quoteId = $this->getQuoteId();
        $quote = $this->mageQuoteRepository->get($quoteId);
        $shippingMethodCode = null;
        $shippingDescription = "Shipping";

        $this->updateOrderAddresses($data, $quote);

        try {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/klarn2.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);

            $logger->info(var_export($_REQUEST, true));
            $logger->info(var_export($_POST, true));
            $logger->info('quote: ' . $quoteId);
            $logger->info(var_export($data, true));
            if ($shippingMethod = $data->getData('selected_shipping_option')) {
                $shippingMethodString = json_encode($shippingMethod, JSON_UNESCAPED_UNICODE);

                $quote->setExtShippingInfo($shippingMethodString);

                $shippingDescription = $shippingMethod['name'];
            } else {
                if ($shippingMethod = $this->getShippingMedthodFromOrderLines($data)) {
                    $shippingMethodCode = $shippingMethod['reference'];
                }
            }
            if (isset($shippingMethodCode)) {
                $shippingMethodCode = $this->convertShippingMethodCode($shippingMethodCode);
                $quote->getShippingAddress()
                    ->setShippingMethod($shippingMethodCode)
                    ->setShippingDescription($shippingDescription)
                    ->setCollectShippingRates(true)
                    ->collectShippingRates();
            }

            $quote->setTotalsCollectedFlag(false)
                ->collectTotals()
                ->save();

        } catch (\Exception $e) {
            $logger->info($e->getMessage());
            $this->logger->info($e->getMessage());
        }

        $orderLines = $data->getOrderLines();
        $i = 0;

        foreach ($orderLines as $orderLine) {
            if ($orderLine['type'] == 'shipping_fee') {
                unset($orderLines[$i]);
            }
            $i++;
        }

        $unitPrice = intval($quote->getShippingAddress()->getShippingInclTax() * 100);
        $totalAmount = intval($quote->getShippingAddress()->getShippingInclTax() * 100);
        $totalTaxAmount = intval($quote->getShippingAddress()->getShippingTaxAmount() * 100);
        $taxRate = $totalTaxAmount ? intval($totalTaxAmount / ($totalAmount - $totalTaxAmount) * 100 * 100) : 0;

        $orderLines[] = [
            'type' => 'shipping_fee',
            'name' => $quote->getShippingAddress()->getShippingDescription(),
            'quantity' => 1,
            'unit_price' => $unitPrice,
            'tax_rate' => $taxRate,
            'total_amount' =>  $totalAmount,
            'total_tax_amount' => $totalTaxAmount
       ];

       $data->setOrderLines(array_values($orderLines));
       $data->setOrderAmount(intval(round($quote->getShippingAddress()->getGrandTotal() * 100)));
       $data->setOrderTaxAmount(intval(round($quote->getShippingAddress()->getTaxAmount() * 100)));

       return $this->resultFactory->create(ResultFactory::TYPE_JSON)
            ->setJsonData($data->toJson())
            ->setHttpResponseCode(200);
    }

    /**
     * @param $shippingCode
     * @return string
     */
    private function convertShippingMethodCode($shippingCode)
    {
        if (!strpos($shippingCode, '_')) return $shippingCode . '_' . $shippingCode;

        return $shippingCode;
    }

    /**
     * @param $shippingCode
     * @return string
     */
    private function convertShippingMethodCodeNew($shippingCode)
    {
        if (!strpos($shippingCode, '_')) return $shippingCode . '_' . $shippingCode;
        $parts = explode('_', $shippingCode);
        if ($parts) {
            return $parts[0] . '_' . $shippingCode;
        }

        return $shippingCode;
    }

    private function getShippingFromKSSCarrierClass($carrierClass) {
        $store = $this->storeManager->getStore();
        $mappings = $this->scopeConfig->getValue('klarna/vsf/carrier_mapping', ScopeInterface::SCOPE_STORES, $store);
        if ($mappings) {
            $mappings = json_decode($mappings, true);
            foreach($mappings as $item) {
                if($item['kss_carrier'] == $carrierClass) {
                    return $item['shipping_method'];
                }
            }
        }
        return '';

    }

    /**
     * @return int
     */
    private function getQuoteId()
    {
        $mask = $this->getKlarnaRequestData()->getData(
            'merchant_reference2'
        );

        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($mask, 'masked_id');
        $quoteId = $quoteIdMask->getQuoteId();
        if( (int)$quoteId==0 && ctype_digit(strval($mask))){
            $quoteId = (int)$mask;
        }
        return $quoteId;
    }

    /**
     * @param DataObject $checkoutData
     * @return bool|mixed
     */
    private function getShippingMedthodFromOrderLines(DataObject $checkoutData)
    {
        $orderLines = $checkoutData->getData('order_lines');

        if (is_array($orderLines)) {
            foreach ($orderLines as $line) {
                if (isset($line['type']) && $line['reference'] && $line['type'] === 'shipping_fee') {
                    return $line;
                }
            }
        }
        return false;
    }

    /**
     * @param DataObject $checkoutData
     * @param \Magento\Quote\Model\Quote|CartInterface $quote
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function updateOrderAddresses(DataObject $checkoutData, CartInterface $quote)
    {
        if (!$checkoutData->hasBillingAddress() && !$checkoutData->hasShippingAddress()) {
            return;
        }

        $sameAsOther = $checkoutData->getShippingAddress() == $checkoutData->getBillingAddress();
        $billingAddress = new DataObject($checkoutData->getBillingAddress());
        $billingAddress->setSameAsOther($sameAsOther);
        $shippingAddress = new DataObject($checkoutData->getShippingAddress());
        $shippingAddress->setSameAsOther($sameAsOther);

        if (!$quote->getCustomerId()) {
            $websiteId = $quote->getStore()->getWebsiteId();
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->loadByEmail($billingAddress->getEmail());
            if (!$customer->getEntityId()) {
                $customer->setWebsiteId($websiteId)
                    ->setStore($quote->getStore())
                    ->setFirstname($billingAddress->getGivenName())
                    ->setLastname($billingAddress->getFamilyName())
                    ->setEmail($billingAddress->getEmail())
                    ->setPassword($billingAddress->getEmail());
                $customer->save();
            }
            $customer = $this->customerRepository->getById($customer->getEntityId());
            $quote->assignCustomer($customer);
        }

        $quote->getBillingAddress()->addData(
            $this->addressDataTransform->prepareMagentoAddress($billingAddress)
        );

        /**
         * @todo  check use 'Billing as shiiping'
         */
        if ($checkoutData->hasShippingAddress()) {
            $quote->setTotalsCollectedFlag(false);
            $quote->getShippingAddress()->addData(
                $this->addressDataTransform->prepareMagentoAddress($shippingAddress)
            );
        }
    }

    /**
     * @return DataObject
     */
    private function getKlarnaRequestData()
    {
        if (null === $this->klarnaRequestData) {
            /** @var \Magento\Framework\App\Request\Http $request */
            $request = $this->getRequest();
            $this->klarnaRequestData = new DataObject(
                json_decode($request->getContent(), true)
            );
        }

        return $this->klarnaRequestData;
    }

    /**
     * Create CSRF validation exception
     *
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Validate for CSRF
     *
     * @param RequestInterface $request
     *
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
