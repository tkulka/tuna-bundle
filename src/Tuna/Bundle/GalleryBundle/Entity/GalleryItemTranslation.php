<?php

namespace TheCodeine\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery_items_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class GalleryItemTranslation extends AbstractPersonalTranslation
{


    /**
     * @ORM\ManyToOne(targetEntity="TheCodeine\GalleryBundle\Entity\GalleryItem", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
