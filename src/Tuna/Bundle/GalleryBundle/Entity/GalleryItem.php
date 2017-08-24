<?php

namespace TunaCMS\Bundle\GalleryBundle\Entity;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TunaCMS\Bundle\FileBundle\Entity\Image;
use TunaCMS\Bundle\VideoBundle\Entity\Video;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use TunaCMS\Bundle\FileBundle\Validator\Constraints as FileAssert;
use TunaCMS\Bundle\GalleryBundle\Validator\Constraints as GalleryAssert;

/**
 * PositionedImage
 *
 * @ORM\Table(name="gallery_items")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="TunaCMS\Bundle\GalleryBundle\Entity\GalleryItemTranslation")
 * @GalleryAssert\GalleryItemFileNotNull
 *
 * @ORM\HasLifecycleCallbacks
 */
class GalleryItem
{
    const VIDEO_TYPE = 'video';
    const IMAGE_TYPE = 'image';

    public static $TYPES = [
        self::VIDEO_TYPE,
        self::IMAGE_TYPE,
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\GalleryBundle\Entity\Gallery", inversedBy="items")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     *
     */
    private $gallery;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @Assert\Valid
     *
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\FileBundle\Entity\Image", cascade={"persist", "remove"})
     **/
    private $image;

    /**
     * @Assert\Valid
     *
     * @var Video
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\VideoBundle\Entity\Video", cascade={"persist"})
     **/
    private $video;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(length=64, nullable=true, type="text")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(length=10, nullable=false, type="string")
     */
    private $type;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="GalleryItemTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function __construct($type = null)
    {
        $this->translations = new ArrayCollection();

        if ($type !== null) {
            $this->setType($type);
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (null === $this->getPosition()) {
            $this->setPosition(0);
        }
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(GalleryItemTranslation $translation)
    {
        if (!$this->translations->contains($translation) && $translation->getContent()) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }

        $this->translations = $translations;
    }

    /**
     * Remove translations
     *
     * @param GalleryItemTranslation $translations
     */
    public function removeTranslation(GalleryItemTranslation $translations)
    {
        $this->translations->removeElement($translations);
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
     * Set position
     *
     * @param integer $position
     * @return GalleryItem
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

    /**
     * Set type
     *
     * @param string $type
     * @return GalleryItem
     */
    public function setType($type)
    {
        if (!in_array($type, self::$TYPES)) {
            throw new \InvalidArgumentException(sprintf(
                "Unknown GalleryItem type (given: '%s', available: '%s')",
                $type,
                implode(', ', self::$TYPES)
            ));
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return GalleryItem
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set video
     *
     * @param Video $video
     * @return GalleryItem
     */
    public function setVideo(Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }


    /**
     * Set gallery
     *
     * @param Gallery $gallery
     * @return GalleryItem
     */
    public function setGallery(Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GalleryItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
