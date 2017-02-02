<?php

namespace TheCodeine\FileBundle\Form;

use TheCodeine\FileBundle\Entity\File;

class FileType extends AbstractFileType
{
    /**
     * {@inheritdoc}
     */
    protected function getEntityClass()
    {
        return File::class;
    }
}
