<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="news_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class NewsTranslation extends AbstractPersonalTranslation
{


    /**
     * @ORM\ManyToOne(targetEntity="News", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
