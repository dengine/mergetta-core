<?php namespace Mergetta\Bus;

use Predis\Client as Redis;

/**
 * Class Bus
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Bus
 */
class Bus {

    private $transport;
    private $services;

    /**
     * @param Redis $transport
     */
    public function __construct(Redis $transport)
    {
        $this->transport = $transport;
        $this->reindexServices();
    }

    /**
     * @param $serviceName
     * @return ServiceProvider
     * @throws ServiceDuplicateException
     */
    public function createService($serviceName)
    {
        if ($this->isServiceRegistered($serviceName)) {
            throw new ServiceDuplicateException($serviceName);
        }
        return new ServiceProvider($this->transport, $serviceName);
    }

    /**
     * @param $serviceName
     * @return ServiceClient
     */
    public function openService($serviceName)
    {
        return new ServiceClient($this->transport, $serviceName);
    }

    /**
     * @param $serviceName
     */
    private function _registerService($serviceName)
    {
        $this->services[] = $serviceName;
    }

    /**
     * @param $serviceName
     * @return bool
     */
    public function isServiceRegistered($serviceName)
    {
        $this->reindexServices();
        return array_search($serviceName, $this->services) !== false;
    }

    /**
     *
     */
    private function reindexServices()
    {
        $this->services = $this->transport->lrange(
            "services._list",
            0,
            $this->transport->llen("services._list") - 1
        );
    }
}