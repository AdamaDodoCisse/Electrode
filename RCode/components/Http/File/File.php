<?php

namespace RCode\Components\Http\File;

/**
 * Class File
 * @package RCode\Components\Http\File
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
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
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
     * @return mixed
     */
    public function getTemporaryPath()
    {
        return $this->temporaryName;
    }

    /**
     * @param $temporaryPath
     */
    public function setTemporaryPath($temporaryPath)
    {
        $this->temporaryPath = $this->expandHomeDirectory($temporaryPath);
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
    }

    /**
     * @param $size
     */
    public function setSize($size)
    {
        $this->size = $size;
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
     * @return string
     */
    public function getMimeType()
    {
        if (is_null($this->type)) {
            $this->setMimeType(mime_content_type($this->getTemporaryName()));
        }
        return $this->type;
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

    /**
     * @param $filename
     * @return string
     */
    protected function expandHomeDirectory($filename) {
        if (isset($_ENV['HOME'])) {
            $home = $_ENV['HOME'];
        } else {
            $home = null;
        }
        return realpath(str_replace("~", $home, $filename));
    }

    /**
     * @param $type
     */
    public function setMimeType($type)
    {
        $this->type = $type;
    }
}