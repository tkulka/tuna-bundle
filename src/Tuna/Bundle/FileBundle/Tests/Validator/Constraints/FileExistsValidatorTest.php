<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use TunaCMS\Bundle\FileBundle\Entity\File;
use TunaCMS\Bundle\FileBundle\Manager\FileManager;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileExists;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileExistsValidator;

class FileExistsValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var FileManager
     */
    private $fileManager;

    protected function setUp()
    {
        $this->fileManager = $this->createMock(FileManager::class);

        parent::setUp();
    }

    protected function createValidator()
    {
        return new FileExistsValidator($this->fileManager);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "TunaCMS\Bundle\FileBundle\Validator\Constraints\FileExists", "Symfony\Component\Validator\Constraints\Blank" given
     */
    public function testInvalidConstraint()
    {
        $this->validator->validate(new File(), new Blank());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "TunaCMS\Bundle\FileBundle\Entity\AbstractFile", "string" given
     */
    public function testInvalidObject()
    {
        $this->validator->validate('foo', new FileExists());
    }

    public function testFilePathIsEmpty()
    {
        $object = new File();

        $this->validator->validate($object, new FileExists());

        $this->assertNoViolation();
    }

    public function testFilePathExists()
    {
        $this->fileManager
            ->expects($this->once())
            ->method('fileExists')
            ->will($this->returnValue(true))
        ;

        $object = new File();
        $object->setPath('/for/test.bar');

        $this->validator->validate($object, new FileExists());

        $this->assertNoViolation();
    }

    public function testFilePathNotExists()
    {
        $object = new File();
        $object->setPath('/for/test.bar');

        $this->validator->validate($object, new FileExists());

        $this->buildViolation('error.file.not_exists')
            ->setInvalidValue('InvalidValue')
            ->setParameter('%filename%', $object->getPath())
            ->assertRaised()
        ;
    }
}
