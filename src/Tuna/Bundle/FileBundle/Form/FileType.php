<?php

namespace TunaCMS\Bundle\FileBundle\Form;

use TunaCMS\Bundle\FileBundle\Entity\File;

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
