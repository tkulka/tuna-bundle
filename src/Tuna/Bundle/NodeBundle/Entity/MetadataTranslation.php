<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="metadata_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MetadataTranslation extends AbstractPersonalTranslation
{
    /**
     * @var MetadataInterface
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\MetadataInterface", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
