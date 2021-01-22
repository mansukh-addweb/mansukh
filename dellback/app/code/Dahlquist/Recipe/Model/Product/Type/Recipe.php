<?php namespace Dahlquist\Recipe\Model\Product\Type;

class Recipe extends \Magento\Catalog\Model\Product\Type\AbstractType
{
    /**
     * Delete data specific for Simple product type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }
}
