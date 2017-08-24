<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface TreeInterface
{
    /**
     * @return TreeInterface
     */
    public function getParent();

    /**
     * @param TreeInterface|null $parent
     *
     * @return $this
     */
    public function setParent(TreeInterface $parent = null);

    /**
     * @return TreeInterface
     */
    public function getRoot();

    /**
     * @param TreeInterface|null $root
     *
     * @return $this
     */
    public function setRoot(TreeInterface $root = null);

    /**
     * @return ArrayCollection|TreeInterface[]
     */
    public function getChildren();

    /**
     * @param ArrayCollection $children
     *
     * @return $this
     */
    public function setChildren(ArrayCollection $children);

    /**
     * @return int
     */
    public function getLft();

    /**
     * @return $this
     *
     * @param int $lft
     */
    public function setLft($lft);

    /**
     * @return int
     */
    public function getRgt();

    /**
     * @return $this
     *
     * @param int $rgt
     */
    public function setRgt($rgt);

    /**
     * @return int
     */
    public function getLvl();

    /**
     * @return $this
     *
     * @param int $lvl
     */
    public function setLvl($lvl);

    /**
     * @param $lft
     * @param $rgt
     * @param $lvl
     * @param null $parent
     *
     * @return $this
     */
    public function setTreeData($lft, $rgt, $lvl, $parent = null);
}
