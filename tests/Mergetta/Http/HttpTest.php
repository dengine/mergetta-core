<?php namespace Mergetta\Http;

use PHPUnit_Framework_TestCase;

class HttpTest extends PHPUnit_Framework_TestCase {

    public function testGetSuccess()
    {
        $this->assertNotEmpty(Http::get('http://www.google.com.ua'));
    }

    /**
     * @expectedException \Mergetta\Http\HttpException
     * @expectedExceptionCode 404
     */
    public function testGetNotFound()
    {
        Http::get('http://www.google.com.ua/qwe123__');
    }

} 