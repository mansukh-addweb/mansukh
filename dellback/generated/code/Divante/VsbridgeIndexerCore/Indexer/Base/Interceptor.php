<?php
namespace Divante\VsbridgeIndexerCore\Indexer\Base;

/**
 * Interceptor class for @see \Divante\VsbridgeIndexerCore\Indexer\Base
 */
class Interceptor extends \Divante\VsbridgeIndexerCore\Indexer\Base implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Divante\VsbridgeIndexerCore\Indexer\Action\ActionFactory $actionFactory, string $typeName)
    {
        $this->___init();
        parent::__construct($actionFactory, $typeName);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($ids)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($ids);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeFull');
        if (!$pluginInfo) {
            return parent::executeFull();
        } else {
            return $this->___callPlugins('executeFull', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeList(array $ids)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeList');
        if (!$pluginInfo) {
            return parent::executeList($ids);
        } else {
            return $this->___callPlugins('executeList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeRow($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeRow');
        if (!$pluginInfo) {
            return parent::executeRow($id);
        } else {
            return $this->___callPlugins('executeRow', func_get_args(), $pluginInfo);
        }
    }
}
