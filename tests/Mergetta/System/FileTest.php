<?php namespace Mergetta\System;

use PHPUnit_Framework_TestCase;

class FileTest extends PHPUnit_Framework_TestCase {

    private $filename;
    private $string;

    public function setUp()
    {
        $this->filename = 'myfile.txt';
        $this->string = 'Hello! :)';
    }

    public function tearDown()
    {
        if (File::exists($this->filename)) {
            File::remove($this->filename);
        }
    }

    /**
     * @expectedException \Mergetta\System\FileExistsException
     */
    public function testDuplicateCreate()
    {
        $this->assertFalse(File::exists($this->filename));
        File::create($this->filename);
        $this->assertTrue(File::exists($this->filename));
        File::create($this->filename);
    }

    public function testRemove()
    {
        File::create($this->filename);
        $this->assertTrue(File::exists($this->filename));
        File::remove($this->filename);
        $this->assertFalse(File::exists($this->filename));
    }

    public function testWrite()
    {
        $file = File::create($this->filename);
        $this->assertEquals(0, File::size($this->filename));
        $file->write($this->string, strlen($this->string));
        $file->close();
    }

    /**
     * @expectedException \Mergetta\System\FileNotFoundException
     */
    public function testOpenNotExists()
    {
        File::create($this->filename);
        File::open($this->filename);
        File::remove($this->filename);
        File::open($this->filename);
    }

    public function testReading()
    {
        $bufferSize = 2;
        $buffer = '';

        $file = File::create($this->filename);
        $file->write($this->string, strlen($this->string));
        $file->close();

        $file = File::open($this->filename);
        while (!$file->isEnd()) {
            $buffer = $buffer . $file->read($bufferSize);
        }
        $this->assertEquals($this->string, $buffer);
    }

    public function testFile()
    {
        $filename = 'myfile.txt';
        $string = 'Hello! :)';

        $this->assertFalse(File::exists($filename));

        $file = File::create($filename);
        $file->write($string, strlen($string));
        $file->close();

        $this->assertTrue(File::exists($filename));
        $this->assertEquals(strlen($string), File::size($filename));

        File::remove($filename);

        $this->assertFalse(File::exists($filename));
    }

} 