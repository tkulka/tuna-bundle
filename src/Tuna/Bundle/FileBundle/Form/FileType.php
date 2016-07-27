<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;
use TheCodeine\FileBundle\Entity\File;

class FileType extends AbstractFileType
{
    protected function getEntityClass()
    {
        return File::class;
    }
}
