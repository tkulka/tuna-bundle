<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface;
use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;

trait NodeTrait
{
    /**
     * @var MenuNodeInterface
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface", mappedBy="node")
     */
    protected $menu;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $controllerAction;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $actionTemplate;

    /**
     * @var MetadataInterface
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\MetadataInterface", cascade={"persist", "remove"})
     */
    protected $metadata;

    /**
     * @return MenuNodeInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return $this
     *
     * @param MenuNodeInterface $menu
     */
    public function setMenu(MenuNodeInterface $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    public function getTypeName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return null|string
     */
    public function getControllerAction()
    {
        return $this->controllerAction;
    }

    /**
     * @param null|string $controllerAction
     *
     * @return $this
     */
    public function setControllerAction($controllerAction = null)
    {
        $this->controllerAction = $controllerAction;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getActionTemplate()
    {
        return $this->actionTemplate;
    }

    /**
     * @param null|string $actionTemplate
     *
     * @return $this
     */
    public function setActionTemplate($actionTemplate = null)
    {
        $this->actionTemplate = $actionTemplate;

        return $this;
    }

    /**
     * @return MetadataInterface
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return $this
     *
     * @param MetadataInterface $metadata
     */
    public function setMetadata(MetadataInterface $metadata = null)
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getSlug()
    {
        if (!$this->getMenu()) {
            return null;
        }

        return $this->getMenu()->getSlug();
    }
}
