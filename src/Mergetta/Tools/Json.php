<?php namespace Mergetta\Tools;

/**
 * Class Json
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Tools
 */
class Json {

    private $data;

    /**
     * @param $data
     */
    private function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param $serialized
     * @return Json
     * @throws JsonException
     */
    public static function fromString($serialized)
    {
        $data = json_decode($serialized);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return new self($data);

            default:
                throw new JsonException(
                    json_last_error_msg(),
                    json_last_error()
                );
        }
    }

    /**
     * @return Json
     */
    public static function create()
    {
        $data = new \stdClass();
        return new self($data);
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return isset($this->data->$name)
            ? $this->data->$name
            : null;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->data->$name = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data);
    }

    public function raw()
    {
        return $this->data;
    }
} 