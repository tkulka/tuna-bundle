<?php

namespace TheCodeine\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\FileBundle\Validator\Constraints as FileAssert;


class AbstractAttachment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Valid
     *
     * @var File
     *
     * @FileAssert\FileNotNull
     * @ORM\ManyToOne(targetEntity="TheCodeine\FileBundle\Entity\File", cascade={"persist", "remove"})
     **/
    protected $file;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, name="title", nullable=true)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="AttachmentTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTranslations(new ArrayCollection());
        $this->setPosition(0);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Attachment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Attachment
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return ArrayCollection|AbstractPersonalTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param AbstractPersonalTranslation $t
     */
    public function addTranslation(AbstractPersonalTranslation $t)
    {
        if (!$this->translations->contains($t) && $t->getContent()) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    /**
     * Remove translations
     *
     * @param AbstractPersonalTranslation $translations
     */
    public function removeTranslation(AttachmentTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     */
    public function setTranslations(ArrayCollection $translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }

        $this->translations = $translations;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return $this
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
