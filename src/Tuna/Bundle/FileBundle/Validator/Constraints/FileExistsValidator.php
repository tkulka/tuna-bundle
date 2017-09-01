<?php

namespace TunaCMS\Bundle\FileBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use TunaCMS\Bundle\FileBundle\Entity\AbstractFile;
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

    public function validate($object, Constraint $constraint)
    {
        if (!$constraint instanceof FileExists) {
            throw new UnexpectedTypeException($constraint, FileExists::class);
        }

        if (!$object instanceof AbstractFile) {
            throw new UnexpectedTypeException($object, AbstractFile::class);
        }

        if ($object->getPath() && !$this->fileManager->fileExists($object)) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('tuna_admin')
                ->setParameter('%filename%', $object->getPath())
                ->addViolation();
        }
    }
}
