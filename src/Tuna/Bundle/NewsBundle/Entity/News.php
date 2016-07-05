<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * News
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Entity\NewsRepository")
 */
class News extends AbstractNews
{
}
