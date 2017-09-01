<?php

namespace TunaCMS\Bundle\GalleryBundle\Tests\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use TunaCMS\Bundle\FileBundle\Entity\Image;
use TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem;
use TunaCMS\Bundle\GalleryBundle\Validator\Constraints\GalleryItemFileNotNull;
use TunaCMS\Bundle\GalleryBundle\Validator\Constraints\GalleryItemFileNotNullValidator;

class GalleryItemFileNotNullValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new GalleryItemFileNotNullValidator();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "TunaCMS\Bundle\GalleryBundle\Validator\Constraints\GalleryItemFileNotNull", "Symfony\Component\Validator\Constraints\Blank" given
     */
    public function testInvalidConstraint()
    {
        $this->validator->validate(new GalleryItem(), new Blank());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem", "string" given
     */
    public function testInvalidObject()
    {
        $this->validator->validate('foo', new GalleryItemFileNotNull());
    }

    public function testTypeIsNotImage()
    {
        $object = new GalleryItem();
        $object->setType(GalleryItem::VIDEO_TYPE);

        $this->validator->validate($object, new GalleryItemFileNotNull());

        $this->assertNoViolation();
    }

    public function testImageIsEmpty()
    {
        $object = new GalleryItem();
        $object->setType(GalleryItem::IMAGE_TYPE);

        $this->validator->validate($object, new GalleryItemFileNotNull());

        $this->buildViolation('error.file.empty')
            ->setInvalidValue('InvalidValue')
            ->atPath('property.path.image')
            ->assertRaised();
    }

    public function testImageIsValid()
    {
        $image = new Image();
        $image->setPath('/foo/test.bar');

        $object = new GalleryItem();
        $object
            ->setType(GalleryItem::IMAGE_TYPE)
            ->setImage($image)
        ;

        $this->validator->validate($object, new GalleryItemFileNotNull());

        $this->assertNoViolation();
    }
}
