<?php namespace Mergetta\Bus;

use Predis\Client as Redis;
use PHPUnit_Framework_TestCase;

class BusTest extends PHPUnit_Framework_TestCase {

    private $bus;

    public function setUp()
    {
        $transport = new Redis();
        $this->bus = new Bus($transport);
    }

    public function tearDown()
    {
        unset($this->bus);
    }

    public function testInstancing()
    {
        $this->assertFalse($this->bus->isServiceRegistered('myService'));

        $serviceProvider = $this->bus->createService('myService');
        $this->assertTrue($this->bus->isServiceRegistered('myService'));

        unset($serviceProvider);
        $this->assertFalse($this->bus->isServiceRegistered('myService'));
    }

    public function testDataTransporting()
    {
        $serviceProvider    = $this->bus->createService('myService');
        $serviceClient      = $this->bus->openService('myService');

        $serviceProvider->create('motd', 'Hello');
        $this->assertEquals('Hello', $serviceClient->get('motd'));

        $serviceProvider->set('motd', 'Bye');
        $this->assertEquals('Bye', $serviceClient->get('motd'));

        $this->assertTrue($serviceClient->exists('motd'));
        $serviceProvider->remove('motd');
        $this->assertFalse($serviceClient->exists('motd'));

        unset($serviceProvider);
    }

    /**
     * @expectedException \Mergetta\Bus\ServiceDuplicateException
     * @expectedExceptionMessage myService
     */
    public function testServiceDuplicateException()
    {
        $this->assertFalse($this->bus->isServiceRegistered('myService'));
        $firstService = $this->bus->createService('myService');
        $this->assertTrue($this->bus->isServiceRegistered('myService'));
        $secondService = $this->bus->createService('myService');
    }

} 