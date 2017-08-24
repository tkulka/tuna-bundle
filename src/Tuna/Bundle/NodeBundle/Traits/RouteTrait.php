<?php

namespace TunaCMS\Bundle\NodeBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="TunaCMS\Bundle\NodeBundle\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"name"})
     * @Gedmo\Translatable
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Gedmo\Translatable
     *
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Translatable
     */
    protected $published;

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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return $this
     *
     * @param string $slug
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @return $this
     *
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;

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
