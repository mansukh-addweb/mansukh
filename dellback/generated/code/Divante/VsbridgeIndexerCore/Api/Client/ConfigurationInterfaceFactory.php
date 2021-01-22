<?php
namespace Divante\VsbridgeIndexerCore\Api\Client;

/**
 * Factory class for @see \Divante\VsbridgeIndexerCore\Api\Client\ConfigurationInterface
 */
class ConfigurationInterfaceFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Divante\\VsbridgeIndexerCore\\Api\\Client\\ConfigurationInterface')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Divante\VsbridgeIndexerCore\Api\Client\ConfigurationInterface
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
