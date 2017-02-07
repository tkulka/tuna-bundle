<?php

namespace TheCodeine\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Page
 *
 * @ORM\Entity(repositoryClass="TheCodeine\PageBundle\Entity\PageRepository")
 */
class Page extends AbstractPage
{
}