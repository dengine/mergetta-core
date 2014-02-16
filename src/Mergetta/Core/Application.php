<?php namespace Mergetta\Core;

/**
 * Class Application
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\Core
 */
abstract class Application {

    const SUCCESS = 0;
    const FAILURE = -1;

    private $arguments;
    private $options;

    public function __construct($argv = null)
    {
        $this->arguments = array();
        $this->options = array();

        // Если $argv не указан, то используем глобальный
        if (is_null($argv)) global $argv;

        // Перебираем по порядку все аргументы, кроме первого
        array_shift($argv);
        foreach ($argv as $arg) {
            if ($this->isOption($arg)) {
                if ($optionName = $this->getOptionName($arg)) {
                    $this->options[$optionName] = true;
                }
            }

            if ($this->isArgument($arg)) {
                $name = $this->getArgumentName($arg);
                $this->arguments[$name] = $this->getArgumentValue($arg);
            }
        }
    }

    /**
     * @param $argumentName
     * @return null
     */
    public function getArgument($argumentName)
    {
        return isset($this->arguments[$argumentName])
            ? $this->arguments[$argumentName]
            : null;
    }

    /**
     * @param $argumentName
     * @return bool
     */
    public function hasArgument($argumentName)
    {
        return array_key_exists($argumentName, $this->arguments);
    }

    /**
     * @param $optionName
     * @return bool
     */
    public function hasOption($optionName)
    {
        return array_key_exists($optionName, $this->options);
    }

    /**
     * @param $argument
     * @return bool
     */
    private function isOption($argument)
    {
        return preg_match('/^-([^=])$/', $argument)
            || preg_match('/^--([^=]+)$/', $argument);
    }

    /**
     * @param $argument
     * @return null
     */
    private function getOptionName($argument)
    {
        if (preg_match('/^-([^=])$/', $argument, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^--([^=]+)$/', $argument, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param $argument
     * @return int
     */
    private function isArgument($argument)
    {
        return preg_match('/^--([^=]+)=(.*)$/', $argument);
    }

    /**
     * @param $argument
     * @return null
     */
    private function getArgumentName($argument)
    {
        if (preg_match('/^--([^=]+)=(.*)$/', $argument, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * @param $argument
     * @return bool|float|int|null|string
     */
    private function getArgumentValue($argument)
    {
        if (preg_match('/^--([^=]+)=(.*)$/', $argument, $matches)) {
            $value = $matches[2];

            // Является ли аргумент числом?
            if (is_numeric($value)) {
                return is_float($value) ? floatval($value) : intval($value);
            }

            // Является ли аргумент булевым значением?
            if (in_array($value, array('true', 'false'))) {
                return $value === 'true';
            }

            // В остальных случаях, считаем аргумент строкой
            return trim($value, ' "');
        }
        return null;
    }

    /**
     * @return mixed
     */
    abstract public function main();
}