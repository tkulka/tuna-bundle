<?php

namespace TheCodeine\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class Image extends AbstractFile
{
}
