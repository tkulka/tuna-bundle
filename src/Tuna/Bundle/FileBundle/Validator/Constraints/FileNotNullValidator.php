<?php

namespace TunaCMS\Bundle\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TunaCMS\Bundle\FileBundle\Entity\AbstractFile;

class FileNotNullValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        self::doValidate($this->context, $constraint, $value);
    }

    public static function doValidate(ExecutionContextInterface $context, Constraint $constraint, AbstractFile $value = null, $atPath = null)
    {
        if ($value == null || $value->getPath() == null) {
            $violationBuilder = $context->buildViolation($constraint->message)
                ->setTranslationDomain('tuna_admin');

            if ($atPath) {
                $violationBuilder->atPath($atPath);
            }

            $violationBuilder->addViolation();
        }
    }
}
