<?php

namespace TunaCMS\Bundle\GalleryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileNotNullValidator;
use TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem;

class GalleryItemFileNotNullValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {
        if (!$constraint instanceof GalleryItemFileNotNull) {
            throw new UnexpectedTypeException($constraint, GalleryItemFileNotNull::class);
        }

        if (!$object instanceof GalleryItem) {
            throw new UnexpectedTypeException($object, GalleryItem::class);
        }

        if ($object->getType() == GalleryItem::IMAGE_TYPE) {
            FileNotNullValidator::doValidate($this->context, $constraint, $object->getImage(), 'image');
        }
    }
}
