<?php

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
        $this->_setFilename_($filename);
        $this->_setParameters_($parameters);
    }

    /**
     * @param string $sectionName
     */
    public function _section_($sectionName)
    {
        $this->sections[] = $sectionName;
        ob_start();
    }

    /**
     * @param string $sectionName
     */
    public function _endSection_($sectionName)
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
     * @param string|null $parent
     */
    public function _extends_($parent)
    {
        if (is_null($parent) || is_string($parent)) {
            $this->parent = $parent;
        } else {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @param $filename
     * @param array $parameters
     */
    public function _include_($filename, array $parameters = array())
    {
        echo (new ElectrodeVS($filename, $parameters))->_renderString_();
    }

    /**
     * @param string $filename
     */
    public function _setFilename_($filename)
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
    public function _setParameters_(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function _renderString_()
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
                $this->_extends_(null);
                return $this->_renderString_();
            } else {
                $this->filename = $this->cycles[0];
                $this->cycles = [];
                return $fileContent;
            }
        }
    }

    public function _render()
    {
        echo $this->_renderString_();
    }
}