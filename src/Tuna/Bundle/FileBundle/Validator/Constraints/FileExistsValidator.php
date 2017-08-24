<?php

namespace TunaCMS\Bundle\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use TunaCMS\Bundle\FileBundle\Manager\FileManager;

class FileExistsValidator extends ConstraintValidator
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * FileExistsValidator constructor.
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function validate($file, Constraint $constraint)
    {
        /* @var $file \TunaCMS\Bundle\FileBundle\Entity\AbstractFile */
        if ($file->getPath() && !$this->fileManager->fileExists($file)) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('tuna_admin')
                ->setParameter('%filename%', $file->getPath())
                ->addViolation();
        }
    }
}
