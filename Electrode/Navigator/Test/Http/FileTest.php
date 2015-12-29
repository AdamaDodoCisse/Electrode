<?php
namespace Electrode\Navigator\Test\Http;

use Electrode\Navigator\Http\File\File;
use PHPUnit_Framework_TestCase;

require_once __DIR__."/../../../../vendor/autoload.php";

class FileTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->file = new File();
        $this->file->setError(0);
        $this->file->setFilename("image.png");
        $this->file->setSize(1000);
        $this->file->setTemporaryPath('tmp/image.01');
    }

    public function testGetError()
    {
        $this->assertEquals($this->file->getError(), 0);
    }

    public function testGetExtension()
    {
        $this->assertEquals($this->file->getExtension(), "png");
    }

    public function testGetSize()
    {
        $this->assertEquals($this->file->getSize(), 1000);
    }

    public function testGetFilename()
    {
        $this->assertSame($this->file->getFilename(), "image.png");
    }
}