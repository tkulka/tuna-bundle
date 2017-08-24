<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert; // workaround for a bug with annotations and nested traits
use TunaCMS\Bundle\MenuBundle\Traits\MenuTrait;

trait NodeTrait
{
    use MenuTrait;
    use RouteTrait;
    use TreeTrait;

    /**
     * @var $this
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Gedmo\TreeRoot
     */
    protected $root;

    /**
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    public function nodeTraitConstructor()
    {
        $this->menuTraitConstructor();
        $this->routeTraitConstructor();
        $this->setPublished(true);
    }
}
