<?php

namespace TheCodeine\NewsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\FileBundle\Entity\Image;
use TheCodeine\TagBundle\Entity\Tag;

interface NewsInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param Tag $tag
     *
     * @return self
     */
    public function addTag(Tag $tag);

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag);

    /**
     * @return ArrayCollection
     */
    public function getTags();

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $body
     *
     * @return self
     */
    public function setBody($body);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param Image $image
     *
     * @return self
     */
    public function setImage(Image $image);

    /**
     * @return Image
     */
    public function getImage();

    /**
     * @param string $teaser
     *
     * @return self
     */
    public function setTeaser($teaser);

    /**
     * @return string
     */
    public function getTeaser();

    /**
     * @param $locale
     * @return mixed
     */
    public function setLocale($locale);

    /**
     * @return mixed
     */
    public function getLocale();

    /**
     * @param Gallery $gallery
     *
     * @return self
     */
    public function setGallery(Gallery $gallery);

    /**
     * @return Gallery
     */
    public function getGallery();

    /**
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt = null);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param boolean $published
     *
     * @return self
     */
    public function setPublished($published);

    /**
     * @return boolean
     */
    public function getPublished();

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @param boolean $important
     *
     * @return self
     */
    public function setImportant($important);

    /**
     * @return boolean
     */
    public function getImportant();

    /**
     * @return boolean
     */
    public function isImportant();

    /**
     * @param ArrayCollection $attachments
     *
     * @return self
     */
    public function setAttachments(ArrayCollection $attachments);

    /**
     * @return ArrayCollection
     */
    public function getAttachments();

    /**
     * @param ArrayCollection $translations
     *
     * @return self
     */
    public function setTranslations(ArrayCollection $translations);

    /**
     * @return ArrayCollection
     */
    public function getTranslations();

    /**
     * @param AbstractPersonalTranslation $translation
     *
     * @return self
     */
    public function addTranslation(AbstractPersonalTranslation $translation);

    /**
     * @param AbstractPersonalTranslation $translation
     *
     * @return self
     */
    public function removeTranslation(AbstractPersonalTranslation $translation);

    /**
     * @return string
     */
    public function getType();
}
