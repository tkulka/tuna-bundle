<?php

namespace TunaCMS\Bundle\MenuBundle\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\NodeBundle\Traits\TreeTrait;
use TunaCMS\CommonComponent\Traits\IdTrait;

trait MenuTrait
{
    use TreeTrait;
    use IdTrait;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $displayingChildren;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank
     */
    protected $label;

    /**
     * @var $this
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\MenuBundle\Model\MenuInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Gedmo\TreeRoot
     */
    protected $root;

    /**
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\MenuBundle\Model\MenuInterface", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="TunaCMS\Bundle\MenuBundle\Model\MenuInterface", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="TunaCMS\Bundle\MenuBundle\Sluggable\Handler\MenuSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"name"})
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $emptySlug;

    public function __construct()
    {
        $this->setDisplayingChildren(true);
        $this->setPublished(true);
        $this->setEmptySlug(false);
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return $this
     *
     * @param string|null $slug
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
    public function setName($name = null)
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
    public function setPublished($published = null)
    {
        $this->published = $published;

        return $this;
    }

    public function isClickable()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEmptySlug()
    {
        return $this->emptySlug;
    }

    /**
     * @return $this
     *
     * @param bool|null $emptySlug
     */
    public function setEmptySlug($emptySlug = null)
    {
        $this->emptySlug = $emptySlug;

        return $this;
    }
}
