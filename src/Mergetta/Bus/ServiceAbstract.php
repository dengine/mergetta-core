<?php namespace Mergetta\Bus;

use Predis\Client as Redis;

/**
 * Class ServiceClient
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Bus
 */
class ServiceAbstract {

    private $serviceName;
    private $transport;
    private $namespace;

    /**
     * @param Redis $transport
     * @param string $serviceName
     */
    public function __construct(Redis $transport, $serviceName)
    {
        $this->serviceName  = $serviceName;
        $this->transport    = $transport;
        $this->namespace    = "services.{$serviceName}";
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return Redis
     */
    protected function getTransport()
    {
        return $this->transport;
    }

    /**
     * @return string
     */
    protected function getServiceName()
    {
        return $this->serviceName;
    }
}