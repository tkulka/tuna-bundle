<?php

namespace TheCodeine\GalleryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GalleryItemFileNotNull extends Constraint
{
    public $message = 'Provide a file dude.';

    public function validatedBy()
    {
        return GalleryItemFileNotNullValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
