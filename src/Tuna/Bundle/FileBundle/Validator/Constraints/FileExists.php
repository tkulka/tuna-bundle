<?php

namespace TheCodeine\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FileExists extends Constraint
{
    public $message = 'Cannot find "%filename%" file in tmp directory.';

    public function validatedBy()
    {
        return FileExistsValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
