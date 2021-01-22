<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\OrderInterface
 */
class OrderExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderExtensionInterface
{
    /**
     * @return \Magento\Sales\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments()
    {
        return $this->_get('shipping_assignments');
    }

    /**
     * @param \Magento\Sales\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments)
    {
        $this->setData('shipping_assignments', $shippingAssignments);
        return $this;
    }

    /**
     * @return \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[]|null
     */
    public function getPaymentAdditionalInfo()
    {
        return $this->_get('payment_additional_info');
    }

    /**
     * @param \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[] $paymentAdditionalInfo
     * @return $this
     */
    public function setPaymentAdditionalInfo($paymentAdditionalInfo)
    {
        $this->setData('payment_additional_info', $paymentAdditionalInfo);
        return $this;
    }

    /**
     * @return \Magento\GiftMessage\Api\Data\MessageInterface|null
     */
    public function getGiftMessage()
    {
        return $this->_get('gift_message');
    }

    /**
     * @param \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage
     * @return $this
     */
    public function setGiftMessage(\Magento\GiftMessage\Api\Data\MessageInterface $giftMessage)
    {
        $this->setData('gift_message', $giftMessage);
        return $this;
    }

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[]|null
     */
    public function getAppliedTaxes()
    {
        return $this->_get('applied_taxes');
    }

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[] $appliedTaxes
     * @return $this
     */
    public function setAppliedTaxes($appliedTaxes)
    {
        $this->setData('applied_taxes', $appliedTaxes);
        return $this;
    }

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[]|null
     */
    public function getItemAppliedTaxes()
    {
        return $this->_get('item_applied_taxes');
    }

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[] $itemAppliedTaxes
     * @return $this
     */
    public function setItemAppliedTaxes($itemAppliedTaxes)
    {
        $this->setData('item_applied_taxes', $itemAppliedTaxes);
        return $this;
    }

    /**
     * @return boolean|null
     */
    public function getConvertingFromQuote()
    {
        return $this->_get('converting_from_quote');
    }

    /**
     * @param boolean $convertingFromQuote
     * @return $this
     */
    public function setConvertingFromQuote($convertingFromQuote)
    {
        $this->setData('converting_from_quote', $convertingFromQuote);
        return $this;
    }

    /**
     * @return \Amazon\Payment\Api\Data\OrderLinkInterface|null
     */
    public function getAmazonOrderReferenceId()
    {
        return $this->_get('amazon_order_reference_id');
    }

    /**
     * @param \Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId
     * @return $this
     */
    public function setAmazonOrderReferenceId(\Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId)
    {
        $this->setData('amazon_order_reference_id', $amazonOrderReferenceId);
        return $this;
    }

    /**
     * @return \Mageplaza\ExtraFee\Api\Data\MpExtraFeeInterface[]|null
     */
    public function getMpExtraFee()
    {
        return $this->_get('mp_extra_fee');
    }

    /**
     * @param \Mageplaza\ExtraFee\Api\Data\MpExtraFeeInterface[] $mpExtraFee
     * @return $this
     */
    public function setMpExtraFee($mpExtraFee)
    {
        $this->setData('mp_extra_fee', $mpExtraFee);
        return $this;
    }

    /**
     * @return \Mediastrategi\UODC\Api\Data\MsuodcAssignmentInterface|null
     */
    public function getMsuodcAssignments()
    {
        return $this->_get('msuodc_assignments');
    }

    /**
     * @param \Mediastrategi\UODC\Api\Data\MsuodcAssignmentInterface $msuodcAssignments
     * @return $this
     */
    public function setMsuodcAssignments(\Mediastrategi\UODC\Api\Data\MsuodcAssignmentInterface $msuodcAssignments)
    {
        $this->setData('msuodc_assignments', $msuodcAssignments);
        return $this;
    }
}
