<?php

namespace Electrode\VS;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class VS
 * @package Electrode\VS
 */
class VS implements \ArrayAccess
{
    /**
     * @var
     */
    private $parent = null;
    /**
     * @var
     */
    private $filename  = null;
    /**
     * @var array
     */
    private $parameters = array();
    /**
     * @var array
     */
    private $cycles = array();
    /**
     * @var array
     */
    private $sections = array();
    /**
     * @var array
     */
    private $blocks = array();

    /**
     * @param string $filename
     * @param array $parameters
     */
    public function __construct($filename, array $parameters = array())
    {
        $this->setFilename($filename);
        $this->setParameters($parameters);
    }

    /**
     * @param $filename
     */
    public function setFilename($filename)
    {
        if (is_string($filename)) {
            $this->filename = $filename;
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param $sectionName
     */
    public function section($sectionName)
    {
        $this->sections[] = $sectionName;
        ob_start();
    }

    /**
     * @param $sectionName
     * @throws RuntimeException
     */
    public function endSection($sectionName)
    {
        $content = ob_get_clean();
        if (!is_string($sectionName)) {
            throw new RuntimeException();
        } else if (empty($this->sections) or array_pop($this->sections) !== $sectionName) {
            throw new RuntimeException();
        } else if (empty($this->blocks[$sectionName])) {
            $this->blocks[$sectionName] = $content;
        }
        echo $this->blocks[$sectionName];
    }

    /**
     * @param $filename
     * @param array $parameters
     */
    public function _include($filename, array $parameters = array())
    {
        echo (new ElectrodeVS($filename, $parameters))->renderString();
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function renderString()
    {
        if (!file_exists($this->filename)) {
            throw new RuntimeException();
        } else if (is_dir($this->filename)) {
            throw new RuntimeException();
        } elseif (!is_readable($this->filename)) {
            throw new RuntimeException();
        } else if (!empty($this->sections)) {
            throw new RuntimeException();
        } else if (in_array($this->filename, $this->cycles)) {
            throw new RuntimeException('Cycle detected in '. $this->filename);
        } else {
            $this->cycles[] = $this->filename;
            ob_start();
            require $this->filename;
            $fileContent = ob_get_clean();
            if (!is_null($this->parent)) {
                $this->filename = $this->parent;
                $this->inherited(null);
                return $this->renderString();
            } else {
                $this->filename = $this->cycles[0];
                $this->cycles = [];
                return $fileContent;
            }
        }
    }

    /**
     * @param $parent
     */
    public function inherited($parent)
    {
        if (is_null($parent) || is_string($parent)) {
            $this->parent = $parent;
        } else {
            throw new InvalidArgumentException();
        }
    }

    public function render()
    {
        echo $this->renderString();
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function getParameter($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
       return isset($this->parameters[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {

       return $this->getParameter($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->parameters[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }
}