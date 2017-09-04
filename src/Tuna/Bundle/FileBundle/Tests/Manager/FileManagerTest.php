<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Manager;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use TunaCMS\Bundle\FileBundle\Entity\File;
use TunaCMS\Bundle\FileBundle\Manager\FileManager;

/**
 * TODO tests:
 *
 * generateFile
 * generateImage
 * moveUploadedFile
 * getFileInfo
 */
class FileManagerTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var HttpFile
     */
    private $httpFile;

    protected function setUp()
    {
        $this->fs = $this->createMock(Filesystem::class);

        $this->fileManager = new FileManager(
            $this->fs, [
                'web_root_dir' => '/root',
                'upload_files_path' => 'upload',
                'tmp_path' => 'tmp',
            ]
        );

        $this->httpFile = $this->createMock(HttpFile::class);

        parent::setUp();
    }

    public function testRemoveFileWhenIsNull()
    {
        $this->fs
            ->expects($this->never())
            ->method('remove')
        ;

        $this->fileManager->removeFile(null);
    }

    public function testRemoveFile()
    {
        $path = 'foo/test.bar';

        $this->fs
            ->expects($this->once())
            ->method('remove')
            ->with('/root/upload/foo/test.bar')
        ;

        $this->fileManager->removeFile($path);
    }

    public function testTmpFileExists()
    {
        $file = new File();
        $file->setPath('foo/test.bar');

        $this->fs
            ->expects($this->once())
            ->method('exists')
            ->with('/root/tmp/foo/test.bar')
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->fileManager->fileExists($file));
    }

    public function testTmpFileNotExists()
    {
        $file = new File();
        $file->setPath('foo/test.bar');

        $this->fs
            ->expects($this->once())
            ->method('exists')
            ->with('/root/tmp/foo/test.bar')
            ->will($this->returnValue(false))
        ;

        $this->assertFalse($this->fileManager->fileExists($file));
    }

    public function testUploadFileExists()
    {
        $file = new File();
        $file->setPath('foo/test.bar');
        $file->savePersistedPath();

        $this->fs
            ->expects($this->once())
            ->method('exists')
            ->with('/root/upload/foo/test.bar')
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->fileManager->fileExists($file));
    }

    public function testUploadFileNotExists()
    {
        $file = new File();
        $file->setPath('foo/test.bar');
        $file->savePersistedPath();

        $this->fs
            ->expects($this->once())
            ->method('exists')
            ->with('/root/upload/foo/test.bar')
            ->will($this->returnValue(false))
        ;

        $this->assertFalse($this->fileManager->fileExists($file));
    }

    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function testMoveTmpFileWhenFileNotExists()
    {
        $file = new File();
        $file->setPath('foo/test.bar');

        $this->fs
            ->expects($this->once())
            ->method('copy')
            ->with('/root/tmp/test.bar', '/root/upload/test.bar')
            ->will($this->throwException(new FileNotFoundException()))
        ;

        $this->fileManager->moveTmpFile($file);
    }

    public function testMoveTmpFile()
    {
        $file = new File();
        $file->setPath('foo/test.bar');

        $this->fs
            ->expects($this->once())
            ->method('copy')
            ->with('/root/tmp/test.bar', '/root/upload/test.bar')
        ;

        $this->fs
            ->expects($this->once())
            ->method('remove')
            ->with('/root/tmp/test.bar')
        ;

        $this->fileManager->moveTmpFile($file);
    }

    public function testGenerateTmpFilename()
    {
        $this->httpFile
            ->expects($this->once())
            ->method('guessExtension')
            ->will($this->returnValue('txt'))
        ;

        $this->assertStringEndsWith(
            '.txt',
            $this->fileManager->generateTmpFilename($this->httpFile)
        );
    }
}
