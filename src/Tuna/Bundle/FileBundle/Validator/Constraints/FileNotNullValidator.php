<?php

namespace TheCodeine\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FileNotNullValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value == null || $value->getPath() == null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
