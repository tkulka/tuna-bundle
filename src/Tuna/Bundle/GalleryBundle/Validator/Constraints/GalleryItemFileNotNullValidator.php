<?php

namespace TheCodeine\GalleryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use TheCodeine\FileBundle\Validator\Constraints\FileNotNullValidator;
use TheCodeine\GalleryBundle\Entity\GalleryItem;

class GalleryItemFileNotNullValidator extends ConstraintValidator
{
    public function validate($galleryItem, Constraint $constraint)
    {
        /* @var $galleryItem \TheCodeine\GalleryBundle\Entity\GalleryItem */
        if ($galleryItem->getType() == GalleryItem::IMAGE_TYPE) {
            FileNotNullValidator::doValidate($this->context, $constraint, $galleryItem->getImage());
        }
    }
}
