<?php

namespace Electrode\Navigator\Http\Request;

use Electrode\Navigator\Http\File\File;

/**
 * Class Request
 * @package RCode\Components\Http\Request
 */
class Request implements RequestInterface
{
    const METHOD_POST = "post";
    const METHOD_GET = "get";
    const METHOD_PUT = "put";
    const METHOD_DELETE = "delete";
    const METHOD_XML_HTTP_REQUEST = "xmlhttprequest";
    /**
     * @var
     */
    private $method;
    /**
     * @var File []
     */
    private $files = array();
    /**
     * @var
     */
    private $url;

    public function __construct()
    {
        $this->captureFiles();
    }

    private function captureFiles()
    {
        foreach ($_FILES as $key => $value) {
            $list = $value;

            foreach ($list['name'] as $k => $v) {
                $file = new File();
                $file->setFilename($v);
                $file->setSize($value['size'][$k]);
                $file->setError($value['error'][$k]);
                $file->setTemporaryPath($value['tmp_name'][$k]);
                $file->setMimeType($value['type'][$k]);
                $this->files[$key][] = $file;
            }
        }
    }

    /**
     * @param $method
     * @return mixed
     */
    public function isMethod($method)
    {
        return $this->getMethod() === $method;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $method
     * @return mixed
     */
    public function setMethod($method)
    {
        $this->method = strtolower((string)$method);
    }

    /**
     * @return mixed
     */
    public function isPOST()
    {
        if (empty($this->getMethod())) {
            return strtolower($_SERVER['REQUEST_METHOD']) === self::METHOD_POST;
        } else {
            return $this->getMethod() === self::METHOD_POST;
        }
    }

    /**
     * @return mixed
     */
    public function isGET()
    {
        if (empty($this->getMethod())) {
            return strtolower($_SERVER['REQUEST_METHOD']) === self::METHOD_GET;
        } else {
            return $this->getMethod() === self::METHOD_GET;
        }
    }

    /**
     * @return mixed
     */
    public function isPUT()
    {
        return $this->getMethod() === self::METHOD_PUT;
    }

    /**
     * @return mixed
     */
    public function isDELETE()
    {
        return $this->getMethod() === self::METHOD_DELETE;
    }

    /**
     * @return mixed
     */
    public function isAJAX()
    {
        if (empty($this->getMethod())) {
            return strtolower($_SERVER['REQUEST_METHOD']) === self::METHOD_XML_HTTP_REQUEST;
        } else {
            return $this->getMethod() === self::METHOD_XML_HTTP_REQUEST;
        }
    }

    /**
     * @return mixed
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * @return File []
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function redirect($url, $status = HTTP_REDIRECT)
    {
        header("location: $url", true, $status);
        exit();
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @return mixed
     */
    public function valueOfGET($key, $defaultValue = null)
    {
        return isset($this->allGET()[$key]) ? $this->allGET()[$key] : $defaultValue;
    }

    /**
     * @return array
     */
    public function allGET()
    {
        return $_GET;
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    public function valueOfPOST($key, $defaultValue = null)
    {
        return isset($this->allPOST()[$key]) ? $this->allPOST()[$key] : $defaultValue;
    }

    /**
     * @return array
     */
    public function allPOST()
    {
        return $_POST;
    }

    /**
     * @return string
     */
    public function getStatus()
    {

    }
}