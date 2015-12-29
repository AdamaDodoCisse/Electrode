<?php

namespace Electrode\Navigator\Http\File;
/**
 * Class File
 * @package Electrode\Navigator\Http\File
 */
class File
{
    /**
     * @var
     */
    private $filename;

    /**
     * @var null
     */
    private $temporaryPath;

    /**
     * @var
     */
    private $error;

    /**
     * @var
     */
    private $size;
    /**
     * @var
     */
    private $type;

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        if (is_null($this->size)) {
            $this->setSize(filesize($this->getTemporaryPath()));
        }
        return $this->size;
    }

    /**
     * @param $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getTemporaryPath()
    {
        return $this->temporaryPath;
    }

    /**
     * @param $temporaryPath
     */
    public function setTemporaryPath($temporaryPath)
    {
        $this->temporaryPath = $this->expandHomeDirectory($temporaryPath);
    }

    /**
     * @param $filename
     * @return string
     */
    protected function expandHomeDirectory($filename)
    {
        if (isset($_ENV['HOME'])) {
            $home = $_ENV['HOME'];
        } else {
            $home = null;
        }
        return realpath(str_replace("~", $home, $filename));
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        if (is_null($this->type)) {
            $this->setMimeType(mime_content_type($this->getTemporaryPath()));
        }
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setMimeType($type)
    {
        $this->type = $type;
    }

    /**
     * @param $directory
     */
    public function upload($directory)
    {
        $directory = $this->expandHomeDirectory($directory);
        $destination = $directory . DIRECTORY_SEPARATOR . $this->getFilename();
        if (is_dir($directory) && file_exists($this->getTemporaryPath())) {
            move_uploaded_file($this->getTemporaryPath(), $destination);
        }
    }

    /**
     *
     */
    public function remove()
    {
        if (file_exists($this->getTemporaryPath())) {
            unlink($this->getTemporaryPath());
        }
    }
}