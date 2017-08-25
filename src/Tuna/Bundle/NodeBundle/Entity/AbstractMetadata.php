<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use A2lix\TranslationFormBundle\Util\GedmoTranslatable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;
use TunaCMS\Bundle\NodeBundle\Traits\MetadataTrait;
use TunaCMS\CommonComponent\Traits\IdTrait;

class AbstractMetadata implements MetadataInterface
{
    use IdTrait;
    use MetadataTrait;
    use GedmoTranslatable;

    /**
     * @var ArrayCollection|MetadataTranslation[]
     * @ORM\OneToMany(targetEntity="TunaCMS\Bundle\NodeBundle\Entity\MetadataTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->setIndexable(true);
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
