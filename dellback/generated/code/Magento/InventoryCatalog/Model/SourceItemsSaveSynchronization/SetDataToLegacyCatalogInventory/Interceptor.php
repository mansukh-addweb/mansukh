<?php
namespace Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory;

/**
 * Interceptor class for @see \Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory
 */
class Interceptor extends \Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\InventoryCatalog\Model\ResourceModel\SetDataToLegacyStockItem $setDataToLegacyStockItem, \Magento\CatalogInventory\Api\StockItemCriteriaInterfaceFactory $legacyStockItemCriteriaFactory, \Magento\CatalogInventory\Api\StockItemRepositoryInterface $legacyStockItemRepository, \Magento\InventoryCatalogApi\Model\GetProductIdsBySkusInterface $getProductIdsBySkus, \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface $stockStateProvider, \Magento\CatalogInventory\Model\Indexer\Stock\Processor $indexerProcessor)
    {
        $this->___init();
        parent::__construct($setDataToLegacyStockItem, $legacyStockItemCriteriaFactory, $legacyStockItemRepository, $getProductIdsBySkus, $stockStateProvider, $indexerProcessor);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $sourceItems) : void
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            parent::execute($sourceItems);
        } else {
            $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
