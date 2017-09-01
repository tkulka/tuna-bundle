<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Twig;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use TunaCMS\Bundle\FileBundle\Entity\File;
use TunaCMS\Bundle\FileBundle\Twig\FileExtension;

class FileExtensionTest extends TestCase
{
    /**
     * @var CacheManager
     */
    private $imagineCacheManager;

    /**
     * @var AssetsHelper
     */
    private $assetsHelper;

    /**
     * @var FileExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->imagineCacheManager = $this->createMock(CacheManager::class);
        $this->assetsHelper = $this->createMock(AssetsHelper::class);

        $this->extension = new FileExtension([
            'web_root_dir' => '/root',
            'upload_files_path' => 'upload',
            'tmp_path' => 'tmp',
        ], $this->assetsHelper, $this->imagineCacheManager);
    }

    public function testGetFileWebPathWhenFileIsNull()
    {
        $this->assertEquals('', $this->extension->getFileWebPath(null));
    }

    public function testGetFileWebPathWhenFileIsNotUploaded()
    {
        $file = new File();
        $file->setPath('path/foo.bar');

        $this->assetsHelper
            ->expects($this->once())
            ->method('getUrl')
            ->with('tmp/path/foo.bar')
            ->will($this->returnValue('tmp/path/foo.bar'))
        ;

        $this->assertEquals(
            'tmp/path/foo.bar',
            $this->extension->getFileWebPath($file)
        );
    }

    public function testGetFileWebPathWhenFileIsUploaded()
    {
        $file = new File();
        $file->setPath('path/foo.bar');
        $file->savePersistedPath();

        $this->assetsHelper
            ->expects($this->once())
            ->method('getUrl')
            ->with('upload/path/foo.bar')
            ->will($this->returnValue('upload/path/foo.bar'))
        ;

        $this->assertEquals(
            'upload/path/foo.bar',
            $this->extension->getFileWebPath($file)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Upload path "invalid" is not defined.
     */
    public function testGetUploadDirWithInvalidName()
    {
        $this->extension->getUploadDir('invalid');
    }

    public function testGetImageWebPathWhenFileIsNull()
    {
        $this->assertEquals('', $this->extension->getImageWebPath(null));
    }

    public function testGetImageWebPathWithoutFilter()
    {
        $file = new File();
        $file->setPath('path/foo.bar');

        $this->assetsHelper
            ->expects($this->once())
            ->method('getUrl')
            ->with('tmp/path/foo.bar')
            ->will($this->returnValue('tmp/path/foo.bar'))
        ;

        $this->assertEquals(
            'tmp/path/foo.bar',
            $this->extension->getImageWebPath($file)
        );
    }


    public function testGetImageWebPathWithFilter()
    {
        $file = new File();
        $file->setPath('path/foo.bar');

        $this->imagineCacheManager
            ->expects($this->once())
            ->method('getBrowserPath')
            ->with('tmp/path/foo.bar', 'filter')
            ->will($this->returnValue('/filter/tmp/path/foo.bar'))
        ;

        $this->assetsHelper
            ->expects($this->once())
            ->method('getUrl')
            ->with('tmp/path/foo.bar')
            ->will($this->returnValue('tmp/path/foo.bar'))
        ;

        $this->assertEquals(
            '/filter/tmp/path/foo.bar',
            $this->extension->getImageWebPath($file, 'filter')
        );
    }

    /**
     * @dataProvider getUploadDirData
     *
     * @param string $name
     * @param string $expected
     */
    public function testGetUploadDir($name, $expected)
    {
        $this->assertEquals($expected, $this->extension->getUploadDir($name));
    }

    public function getUploadDirData()
    {
        return [
            [
                'web_root_dir',
                '/root',
            ],
            [
                'upload_files_path',
                'upload',
            ],
            [
                'tmp_path',
                'tmp',
            ],
        ];
    }
}
