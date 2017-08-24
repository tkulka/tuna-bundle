<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TunaCMS\Bundle\NodeBundle\Model\TreeInterface;

/**
 * Trait TreeTrait
 *
 * remember to implement `root`, `parent` and `children` fields yourself (they must have proper association classes)
 */
trait TreeTrait
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\TreeLeft
     */
    protected $lft;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\TreeRight
     */
    protected $rgt;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Gedmo\TreeLevel
     */
    protected $lvl;

    /**
     * @return TreeInterface
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return $this
     *
     * @param TreeInterface|null $root
     */
    public function setRoot(TreeInterface $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return TreeInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return $this
     *
     * @param TreeInterface|null $parent
     */
    public function setParent(TreeInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ArrayCollection|TreeInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return $this
     *
     * @param ArrayCollection $children
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return int
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @return $this
     *
     * @param int $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * @return int
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @return $this
     *
     * @param int $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * @return int
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @return $this
     *
     * @param int $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * @param $lft
     * @param $rgt
     * @param $lvl
     * @param null $parent
     *
     * @return $this
     */
    public function setTreeData($lft, $rgt, $lvl, $parent = null)
    {
        $this->lft = $lft;
        $this->rgt = $rgt;
        $this->lvl = $lvl;
        $this->parent = $parent;

        return $this;
    }
}
