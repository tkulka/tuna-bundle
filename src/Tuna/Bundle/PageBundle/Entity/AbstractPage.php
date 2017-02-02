<?php

namespace TheCodeine\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\ImageBundle\Entity\Image;

/**
 * AbstractPage
 *
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractPage
{
    /**
     * @var string
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="teaser", type="text", nullable=true)
     */
    protected $teaser;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    protected $body;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToOne(targetEntity="TheCodeine\FileBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $image;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToOne(targetEntity="TheCodeine\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     */
    protected $gallery;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @Assert\Valid
     *
     * @ORM\ManyToMany(targetEntity="TheCodeine\FileBundle\Entity\Attachment", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true)}
     *      )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $attachments;

    /**
     * @Gedmo\Locale
     */
    protected $locale;
    protected $translations;

    /**
     * AbstractPage constructor.
     *
     * @param null|string $title
     */
    public function __construct($title = null)
    {
        $this->attachments = new ArrayCollection();
        $this->translations = new ArrayCollection();

        if ($title !== null) {
            $this->setTitle($title);
        }

        $this->setPublished(false);
    }

    /**
     * @param Gallery $gallery
     *
     * @return AbstractPage
     */
    public function setGallery(Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param $body
     *
     * @return AbstractPage
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Image $image
     *
     * @return AbstractPage
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $title
     *
     * @return AbstractPage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $locale
     *
     * @return AbstractPage
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param AbstractPersonalTranslation $t
     *
     * @return AbstractPage
     */
    public function addTranslation(AbstractPersonalTranslation $t)
    {
        if (!$this->translations->contains($t) && $t->getContent()) {
            $this->translations[] = $t;
            $t->setObject($this);
        }

        return $this;
    }

    /**
     * @param AbstractPersonalTranslation $t
     *
     * @return AbstractPage
     */
    public function removeTranslation(AbstractPersonalTranslation $t)
    {
        $this->translations->removeElement($t);

        return $this;
    }

    /**
     * @param ArrayCollection[AbstractPersonalTranslation] $translations
     *
     * @return AbstractPage
     */
    public function setTranslations($translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }

        $this->translations = $translations;

        return $this;
    }

    /**
     * @param boolean $published
     *
     * @return AbstractPage
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param ArrayCollection $attachments
     *
     * @return AbstractPage
     */
    public function setAttachments(ArrayCollection $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     *
     * @return AbstractPage
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }
}
