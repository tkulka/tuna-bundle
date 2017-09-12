<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Validator\Constraints;

use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileNotNull;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileNotNullValidator;
use TunaCMS\Bundle\FileBundle\Entity\File;

class FileNotNullValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new FileNotNullValidator();
    }

    public function testObjectIsNull()
    {
        $this->validator->validate(null, new FileNotNull());

        $this->buildViolation('error.file.empty')
            ->setInvalidValue('InvalidValue')
            ->assertRaised()
        ;
    }

    public function testFilePathIsEmpty()
    {
        $object = new File();

        $this->validator->validate($object, new FileNotNull());

        $this->buildViolation('error.file.empty')
            ->setInvalidValue('InvalidValue')
            ->assertRaised()
        ;
    }

    public function testFilePathIsValid()
    {
        $object = new File();
        $object->setPath('/foo/test.bar');

        $this->validator->validate($object, new FileNotNull());

        $this->assertNoViolation();
    }
}
