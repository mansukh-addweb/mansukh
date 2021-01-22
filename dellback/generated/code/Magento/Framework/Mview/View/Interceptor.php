<?php
namespace Magento\Framework\Mview\View;

/**
 * Interceptor class for @see \Magento\Framework\Mview\View
 */
class Interceptor extends \Magento\Framework\Mview\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Mview\ConfigInterface $config, \Magento\Framework\Mview\ActionFactory $actionFactory, \Magento\Framework\Mview\View\StateInterface $state, \Magento\Framework\Mview\View\ChangelogInterface $changelog, \Magento\Framework\Mview\View\SubscriptionFactory $subscriptionFactory, array $data = [], array $changelogBatchSize = [])
    {
        $this->___init();
        parent::__construct($config, $actionFactory, $state, $changelog, $subscriptionFactory, $data, $changelogBatchSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getId');
        if (!$pluginInfo) {
            return parent::getId();
        } else {
            return $this->___callPlugins('getId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setId');
        if (!$pluginInfo) {
            return parent::setId($id);
        } else {
            return $this->___callPlugins('setId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIdFieldName($name)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIdFieldName');
        if (!$pluginInfo) {
            return parent::setIdFieldName($name);
        } else {
            return $this->___callPlugins('setIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdFieldName');
        if (!$pluginInfo) {
            return parent::getIdFieldName();
        } else {
            return $this->___callPlugins('getIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionClass()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionClass');
        if (!$pluginInfo) {
            return parent::getActionClass();
        } else {
            return $this->___callPlugins('getActionClass', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGroup');
        if (!$pluginInfo) {
            return parent::getGroup();
        } else {
            return $this->___callPlugins('getGroup', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscriptions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubscriptions');
        if (!$pluginInfo) {
            return parent::getSubscriptions();
        } else {
            return $this->___callPlugins('getSubscriptions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($viewId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($viewId);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'subscribe');
        if (!$pluginInfo) {
            return parent::subscribe();
        } else {
            return $this->___callPlugins('subscribe', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsubscribe');
        if (!$pluginInfo) {
            return parent::unsubscribe();
        } else {
            return $this->___callPlugins('unsubscribe', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'update');
        if (!$pluginInfo) {
            return parent::update();
        } else {
            return $this->___callPlugins('update', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function suspend()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'suspend');
        if (!$pluginInfo) {
            return parent::suspend();
        } else {
            return $this->___callPlugins('suspend', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resume()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resume');
        if (!$pluginInfo) {
            return parent::resume();
        } else {
            return $this->___callPlugins('resume', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearChangelog()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearChangelog');
        if (!$pluginInfo) {
            return parent::clearChangelog();
        } else {
            return $this->___callPlugins('clearChangelog', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getState');
        if (!$pluginInfo) {
            return parent::getState();
        } else {
            return $this->___callPlugins('getState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setState(\Magento\Framework\Mview\View\StateInterface $state)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setState');
        if (!$pluginInfo) {
            return parent::setState($state);
        } else {
            return $this->___callPlugins('setState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEnabled');
        if (!$pluginInfo) {
            return parent::isEnabled();
        } else {
            return $this->___callPlugins('isEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isIdle()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isIdle');
        if (!$pluginInfo) {
            return parent::isIdle();
        } else {
            return $this->___callPlugins('isIdle', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isWorking()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isWorking');
        if (!$pluginInfo) {
            return parent::isWorking();
        } else {
            return $this->___callPlugins('isWorking', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSuspended()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSuspended');
        if (!$pluginInfo) {
            return parent::isSuspended();
        } else {
            return $this->___callPlugins('isSuspended', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdated()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUpdated');
        if (!$pluginInfo) {
            return parent::getUpdated();
        } else {
            return $this->___callPlugins('getUpdated', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChangelog()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getChangelog');
        if (!$pluginInfo) {
            return parent::getChangelog();
        } else {
            return $this->___callPlugins('getChangelog', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($key, $value);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetData');
        if (!$pluginInfo) {
            return parent::unsetData($key);
        } else {
            return $this->___callPlugins('unsetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByPath');
        if (!$pluginInfo) {
            return parent::getDataByPath($path);
        } else {
            return $this->___callPlugins('getDataByPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByKey');
        if (!$pluginInfo) {
            return parent::getDataByKey($key);
        } else {
            return $this->___callPlugins('getDataByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataUsingMethod');
        if (!$pluginInfo) {
            return parent::setDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('setDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataUsingMethod');
        if (!$pluginInfo) {
            return parent::getDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('getDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasData');
        if (!$pluginInfo) {
            return parent::hasData($key);
        } else {
            return $this->___callPlugins('hasData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($keys);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToArray');
        if (!$pluginInfo) {
            return parent::convertToArray($keys);
        } else {
            return $this->___callPlugins('convertToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml($keys, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToXml');
        if (!$pluginInfo) {
            return parent::convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('convertToXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toJson');
        if (!$pluginInfo) {
            return parent::toJson($keys);
        } else {
            return $this->___callPlugins('toJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToJson');
        if (!$pluginInfo) {
            return parent::convertToJson($keys);
        } else {
            return $this->___callPlugins('convertToJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toString');
        if (!$pluginInfo) {
            return parent::toString($format);
        } else {
            return $this->___callPlugins('toString', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__call');
        if (!$pluginInfo) {
            return parent::__call($method, $args);
        } else {
            return $this->___callPlugins('__call', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEmpty');
        if (!$pluginInfo) {
            return parent::isEmpty();
        } else {
            return $this->___callPlugins('isEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'serialize');
        if (!$pluginInfo) {
            return parent::serialize($keys, $valueSeparator, $fieldSeparator, $quote);
        } else {
            return $this->___callPlugins('serialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debug');
        if (!$pluginInfo) {
            return parent::debug($data, $objects);
        } else {
            return $this->___callPlugins('debug', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetSet');
        if (!$pluginInfo) {
            return parent::offsetSet($offset, $value);
        } else {
            return $this->___callPlugins('offsetSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetExists');
        if (!$pluginInfo) {
            return parent::offsetExists($offset);
        } else {
            return $this->___callPlugins('offsetExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetUnset');
        if (!$pluginInfo) {
            return parent::offsetUnset($offset);
        } else {
            return $this->___callPlugins('offsetUnset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetGet');
        if (!$pluginInfo) {
            return parent::offsetGet($offset);
        } else {
            return $this->___callPlugins('offsetGet', func_get_args(), $pluginInfo);
        }
    }
}
