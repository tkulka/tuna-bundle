<?php

namespace TunaCMS\Bundle\MenuBundle\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use Doctrine\ORM\Mapping as ORM;

trait MenuTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $clickable;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $displayingChildren;

    /**
     * @var NodeInterface|null
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\NodeInterface", cascade={"persist"})
     */
    protected $node;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank
     * @Gedmo\Translatable
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    protected $linkType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $url;

    public function menuTraitConstructor()
    {
        $this->setClickable(true);
        $this->setDisplayingChildren(true);
        $this->setLinkType(MenuInterface::LINK_NODE);
    }

    /**
     * @return bool
     */
    public function isClickable()
    {
        return $this->clickable;
    }

    /**
     * @return $this
     *
     * @param bool $clickable
     */
    public function setClickable($clickable)
    {
        $this->clickable = $clickable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayingChildren()
    {
        return $this->displayingChildren;
    }

    /**
     * @return $this
     *
     * @param bool $displayingChildren
     */
    public function setDisplayingChildren($displayingChildren)
    {
        $this->displayingChildren = $displayingChildren;

        return $this;
    }

    /**
     * @return NodeInterface|null
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return $this
     *
     * @param NodeInterface|null $node
     */
    public function setNode(NodeInterface $node = null)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return $this
     *
     * @param null|string $label
     */
    public function setLabel($label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * @return $this
     *
     * @param string $linkType
     */
    public function setLinkType($linkType)
    {
        $this->linkType = $linkType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return $this
     *
     * @param null|string $url
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }
}
