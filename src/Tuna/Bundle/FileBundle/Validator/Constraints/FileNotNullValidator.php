<?php

namespace TheCodeine\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TheCodeine\FileBundle\Entity\AbstractFile;

class FileNotNullValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        self::doValidate($this->context, $constraint, $value);
    }

    public static function doValidate(ExecutionContextInterface $context, Constraint $constraint, AbstractFile $value = null)
    {
        if ($value == null || $value->getPath() == null) {
            $context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
