<?php namespace Mergetta\Bus;

use Predis\Client as Redis;

/**
 * Class ServiceProvider
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Bus
 */
class ServiceProvider extends ServiceAbstract {

    /**
     * @param Redis $transport
     * @param $serviceName
     */
    public function __construct(Redis $transport, $serviceName)
    {
        parent::__construct($transport, $serviceName);
        $this->registerService($this->getServiceName());
    }

    /**
     *
     */
    function __destruct()
    {
        $this->unregisterService($this->getServiceName());
    }

    /**
     *
     */
    public function create($name, $value = null)
    {
        $this->registerVariable($name);
        $this->set($name, $value);
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->getTransport()
            ->hset("{$this->getNamespace()}.values", $name, $value);
    }

    /**
     * @param $name
     */
    public function remove($name)
    {
        $this->getTransport()
            ->hdel("{$this->getNamespace()}.values", $name);
        $this->unregisterVariable($name);
    }

    /**
     * @param $name
     */
    private function registerVariable($name)
    {
        $this->getTransport()
            ->rpush("{$this->getNamespace()}.variables", $name);
    }

    /**
     * @param $name
     */
    private function unregisterVariable($name)
    {
        $this->getTransport()
            ->lrem("{$this->getNamespace()}.variables", 0, $name);
    }

    /**
     * @param $serviceName
     */
    private function registerService($serviceName)
    {
        $this->getTransport()
            ->rpush("services._list", $serviceName);
    }

    /**
     * @param $serviceName
     */
    private function unregisterService($serviceName)
    {
        $this->getTransport()
            ->lrem("services._list", 0, $serviceName);
    }
} 