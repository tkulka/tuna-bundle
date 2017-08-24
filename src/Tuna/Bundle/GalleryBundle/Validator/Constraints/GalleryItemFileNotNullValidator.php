<?php

namespace TunaCMS\Bundle\GalleryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use TunaCMS\Bundle\FileBundle\Validator\Constraints\FileNotNullValidator;
use TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem;

class GalleryItemFileNotNullValidator extends ConstraintValidator
{
    public function validate($galleryItem, Constraint $constraint)
    {
        /* @var $galleryItem \TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem */
        if ($galleryItem->getType() == GalleryItem::IMAGE_TYPE) {
            FileNotNullValidator::doValidate($this->context, $constraint, $galleryItem->getImage(), 'image');
        }
    }
}
