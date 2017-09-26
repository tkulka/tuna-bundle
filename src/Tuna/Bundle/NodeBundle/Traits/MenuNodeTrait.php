<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

trait MenuNodeTrait
{
    /**
     * @var NodeInterface
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", cascade={"persist", "remove"}, inversedBy="menu")
     */
    protected $node;

    /**
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return $this
     *
     * @param NodeInterface $node
     */
    public function setNode(NodeInterface $node = null)
    {
        $this->node = $node;
        $node->setMenu($this);

        return $this;
    }
}
