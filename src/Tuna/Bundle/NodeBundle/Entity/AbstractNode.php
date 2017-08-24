<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use A2lix\TranslationFormBundle\Util\GedmoTranslatable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Traits\NodeTrait;
use TunaCMS\CommonComponent\Traits\IdTrait;

abstract class AbstractNode implements NodeInterface
{
    use IdTrait;
    use NodeTrait;
    use GedmoTranslatable;

    /**
     * @var ArrayCollection|NodeTranslation[]
     *
     * @ORM\OneToMany(targetEntity="TunaCMS\Bundle\NodeBundle\Entity\NodeTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->nodeTraitConstructor();

        $this->translations = new ArrayCollection();
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getNode()
    {
        return $this;
    }

    public function setNode(NodeInterface $node = null)
    {
        return $this;
    }
}
