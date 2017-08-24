<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="node_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class NodeTranslation extends AbstractPersonalTranslation
{
    /**
     * @var NodeInterface
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    public function __construct($field = null, $locale = null, $content = null)
    {
        $this->setField($field);
        $this->setLocale($locale);
        $this->setContent($content);
    }
}
