<?php namespace Mergetta\System;

/**
 * Class File
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\System
 */
class File {

    private $resource;

    /**
     * @param $resource
     */
    private function __construct($resource)
    {
        $this->resource = $resource;
    }

    function __destruct()
    {
        if ($this->resource) {
            $this->close();
        }
    }

    /**
     * @param $buffer
     * @param null $length
     */
    public function write($buffer, $length = null)
    {
        fwrite($this->resource, $buffer, $length);
    }

    /**
     * @param $length
     * @return string
     */
    public function read($length)
    {
        return fread($this->resource, $length);
    }

    /**
     * @return bool
     */
    public function isEnd()
    {
        return feof($this->resource);
    }

    /**
     *
     */
    public function close()
    {
        fclose($this->resource);
        $this->resource = null;
    }

    public static function create($filename, $binary = false)
    {
        if (file_exists($filename)) {
            throw new FileExistsException($filename);
        }

        $resource = fopen($filename, 'x+');
        return new self($resource);
    }

    public static function exists($filename)
    {
        return file_exists($filename);
    }

    public static function size($filename)
    {
        return filesize($filename);
    }

    public static function remove($filename)
    {
        unlink($filename);
    }

    public static function open($filename)
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException($filename);
        }

        $resource = fopen($filename, 'r+');
        return new self($resource);
    }
}