<?php

namespace TheCodeine\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="TheCodeine\GalleryBundle\Entity\GalleryItem", mappedBy="gallery", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    private $items;

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
     * Add item
     *
     * @param GalleryItem $item
     *
     * @return $this
     */
    public function addItem(GalleryItem $item)
    {
        $item->setGallery($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param GalleryItem $item
     */
    public function removeItem(GalleryItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}