<?php namespace Mergetta\Bus;

use Predis\Client as Redis;

/**
 * Class ServiceClient
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Bus
 */
class ServiceClient extends ServiceAbstract {

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->getTransport()
            ->hget("{$this->getNamespace()}.values", $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name)
    {
        return $this->getTransport()
            ->hexists("{$this->getNamespace()}.values", $name);
    }
}