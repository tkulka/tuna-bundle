<?php

namespace TheCodeine\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="TheCodeine\PageBundle\Entity\PageRepository")
 * @ORM\EntityListeners({"TheCodeine\PageBundle\EventListener\PageAliasListener"})
 * @Gedmo\TranslationEntity(class="TheCodeine\PageBundle\Entity\PageTranslation")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Page extends AbstractPage
{
    /**
     * @ORM\OneToMany(targetEntity="PageTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $alias;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return $this
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }
}
