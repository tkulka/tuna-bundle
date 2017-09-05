<?php

namespace TunaCMS\Bundle\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use TunaCMS\CommonComponent\Traits\TranslatableAccessorTrait;

/**
 * Attachment
 *
 * @ORM\Entity
 * @ORM\Table(name="attachments")
 *
 * @method Attachment setTitle(string $title)
 * @method string getTitle()
 */
class Attachment extends AbstractAttachment
{
    use ORMBehaviors\Translatable\Translatable;
    use TranslatableAccessorTrait;
}
