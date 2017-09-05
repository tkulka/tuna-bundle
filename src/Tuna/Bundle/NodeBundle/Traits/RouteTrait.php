<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;

trait RouteTrait
{
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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $rootOfATree;

    /**
     * @var MetadataInterface
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\NodeBundle\Model\MetadataInterface", cascade={"persist", "remove"})
     */
    protected $metadata;

    public function routeTraitConstructor()
    {
        $this->setRootOfATree(false);
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
     * @return bool
     */
    public function isRootOfATree()
    {
        return $this->rootOfATree;
    }

    /**
     * @return $this
     *
     * @param bool $rootOfATree
     */
    public function setRootOfATree($rootOfATree)
    {
        $this->rootOfATree = $rootOfATree;

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
}
