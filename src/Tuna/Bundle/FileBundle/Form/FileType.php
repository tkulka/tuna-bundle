<?php

namespace TheCodeine\FileBundle\Form;

use TheCodeine\FileBundle\Entity\File;

class FileType extends AbstractFileType
{
    protected function getEntityClass()
    {
        return File::class;
    }
}
