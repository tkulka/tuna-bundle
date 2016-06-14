<?php

namespace TheCodeine\PageBundle\Entity;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use TheCodeine\NewsBundle\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\NewsBundle\Entity\Attachment;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="TheCodeine\PageBundle\Entity\PageTranslation")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Page extends BasePage
{
    /**
     * @ORM\OneToMany(targetEntity="PageTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @ORM\ManyToMany(targetEntity="TheCodeine\NewsBundle\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinTable(name="page_attachments",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true)}
     *      )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $attachments;
}
