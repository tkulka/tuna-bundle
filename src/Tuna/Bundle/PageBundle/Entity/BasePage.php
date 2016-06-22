<?php

namespace TheCodeine\PageBundle\Entity;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\NewsBundle\Entity\Attachment;

/**
 * Page
 *
 * @ORM\HasLifecycleCallbacks
 */
abstract class BasePage
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
     * @ORM\OneToOne(targetEntity="\TheCodeine\ImageBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    protected $image;

    protected $translations;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    /**
     * @ORM\OneToOne(targetEntity="TheCodeine\GalleryBundle\Entity\Gallery",cascade={"persist"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     */
    protected $gallery;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published;

    protected $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->setPublished(false);
    }

    /**
     * Set gallery
     *
     * @param \TheCodeine\GalleryBundle\Entity\Gallery $gallery
     * @return News
     */
    public function setGallery(Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \TheCodeine\GalleryBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $title
     * @return Page
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
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(AbstractPersonalTranslation $t)
    {
        if (!$this->translations->contains($t) && $t->getContent()) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(AbstractPersonalTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    /**
     * Set translations
     *
     * @param ArrayCollection [AbstractPersonalTranslation] $translations
     */
    public function setTranslations($translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }

        $this->translations = $translations;
    }

    /**
     * Set published flag
     *
     * @param boolean $published
     * @return Page
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get publish flag
     *
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Add attachments
     *
     * @param \TheCodeine\NewsBundle\Entity\Attachment $attachment
     * @return News
     */
    public function addAttachment(\TheCodeine\NewsBundle\Entity\Attachment $attachment)
    {

        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \TheCodeine\NewsBundle\Entity\Attachment $attachment
     */
    public function removeAttachment(Attachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection
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
     * @return $this
     * @param string $teaser
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }
}
