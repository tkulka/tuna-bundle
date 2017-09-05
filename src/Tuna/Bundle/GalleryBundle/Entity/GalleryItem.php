<?php

namespace TunaCMS\Bundle\GalleryBundle\Entity;

use TunaCMS\Bundle\FileBundle\Entity\Image;
use TunaCMS\Bundle\VideoBundle\Entity\Video;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use TunaCMS\Bundle\GalleryBundle\Validator\Constraints as GalleryAssert;
use TunaCMS\CommonComponent\Traits\TranslatableAccessorTrait;

/**
 * GalleryItem
 *
 * @ORM\Table(name="gallery_items")
 * @ORM\Entity
 * @GalleryAssert\GalleryItemFileNotNull
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @method GalleryItem setName(string $name)
 * @method string getName()
 */
class GalleryItem
{
    use ORMBehaviors\Translatable\Translatable;
    use TranslatableAccessorTrait;

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
     * @ORM\Column(length=10, nullable=false, type="string")
     */
    private $type;

    public function __construct($type = null)
    {
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
}
