<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class MenuTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="TheCodeine\MenuBundle\Entity\MenuInterface", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * MenuTranslation constructor.
     */
    public function __construct($field = null, $locale = null, $content = null)
    {
        $this->setField($field);
        $this->setLocale($locale);
        $this->setContent($content);
    }
}
