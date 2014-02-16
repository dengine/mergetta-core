<?php namespace Mergetta\Tools;

use PHPUnit_Framework_TestCase;

class JsonTest extends PHPUnit_Framework_TestCase {

    private $string;

    public function setUp()
    {
        $this->string = '{
            "a": 1,
            "b": "a",
            "c": true,
            "d": 3.14,
            "e": null,
            "f": [1,2,3],
            "g": {
                "a": 1,
                "b": 2
            }
        }';
    }

    public function testFromString()
    {
        $object = Json::fromString($this->string);

        $this->assertEquals(1, $object->a);
        $this->assertInternalType('integer', $object->a);

        $this->assertEquals('a', $object->b);
        $this->assertInternalType('string', $object->b);

        $this->assertTrue($object->c);
        $this->assertInternalType('bool', $object->c);

        $this->assertEquals(3.14, $object->d);
        $this->assertInternalType('float', $object->d);

        $this->assertNull($object->e);

        $this->assertCount(3, $object->f);
        $this->assertInternalType('array', $object->f);

        $this->assertObjectHasAttribute('a', $object->g);
        $this->assertObjectHasAttribute('b', $object->g);
        $this->assertObjectNotHasAttribute('c', $object->g);
        $this->assertInternalType('object', $object->g);
    }

    /**
     * @expectedException \Mergetta\Tools\JsonException
     * @expectedExceptionMessage Syntax error
     */
    public function testInvalidJson()
    {
        Json::fromString('___');
    }

    public function testCreateJson()
    {
        $object = Json::create();
        $object->a = 123;
        $object->b = "qwe";

        $this->assertEquals(
            '{"a":123,"b":"qwe"}',
            (string) $object
        );
    }

} 