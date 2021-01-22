<?php
namespace Divante\VsbridgeIndexerCore\Indexer;

/**
 * Factory class for @see \Divante\VsbridgeIndexerCore\Indexer\GenericIndexerHandler
 */
class GenericIndexerHandlerFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Divante\\VsbridgeIndexerCore\\Indexer\\GenericIndexerHandler')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Divante\VsbridgeIndexerCore\Indexer\GenericIndexerHandler
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
