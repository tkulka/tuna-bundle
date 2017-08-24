<?php

namespace TunaCMS\Bundle\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection|GalleryItem[]
     *
     * @ORM\OneToMany(targetEntity="TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem", mappedBy="gallery", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    private $items;

    /**
     * Gallery constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @param PersistentCollection|array $items
     *
     * @return Gallery
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $item->setGallery($this);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * @return ArrayCollection|GalleryItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
