<?php

namespace Electrode\ElectrodeVS;

use InvalidArgumentException;
use RuntimeException;

class ElectrodeVS
{

    private $parent;

    private $filename;

    private $parameters = array();

    private $cycles = array();

    private $sections = array();

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
            throw new RuntimeException();
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
}