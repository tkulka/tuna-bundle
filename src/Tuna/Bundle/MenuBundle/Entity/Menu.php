<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\Page;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="MenuRepository")
 * @Gedmo\TranslationEntity(class="TheCodeine\MenuBundle\Entity\MenuTranslation")
 */
class Menu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    protected $label;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @var string
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"path"})
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="TheCodeine\PageBundle\Entity\Page")
     */
    protected $page;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $clickable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishDate;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="MenuTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Menu")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * Menu constructor.
     * @param string $label
     * @param Page $page
     * @param bool $clickable
     * @param \DateTime $publishDate
     */
    public function __construct($label = null, Menu $parent = null, $clickable = true, \DateTime $publishDate = null, Page $page = null)
    {
        $this->setLabel($label);
        $this->setParent($parent);
        $this->setClickable($clickable);
        $this->setPublishDate($publishDate);
        $this->setPage($page);

        $this->translations = new ArrayCollection();
    }

    public function getIndentedName()
    {
        return str_repeat("--", $this->lvl) . ' ' . $this->getLabel();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Menu $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return $this
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->setPath($label);
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
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return $this
     * @param Page $page
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isClickable()
    {
        return $this->clickable;
    }

    /**
     * @return $this
     * @param boolean $clickable
     */
    public function setClickable($clickable)
    {
        $this->clickable = $clickable;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @return $this
     * @param \DateTime $publishDate
     */
    public function setPublishDate(\DateTime $publishDate = null)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * @param $published boolean
     */
    public function setPublished($published)
    {
        if ($published !== false) {
            $this->setPublishDate(new \DateTime());
        } else {
            $this->setPublishDate(null);
        }
    }

    public function isPublished()
    {
        return $this->getPublishDate() !== null && $this->getPublishDate() <= (new \DateTime());
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return $this
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(AbstractPersonalTranslation $t)
    {
        if (!$this->translations->contains($t) && $t->getContent()) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(AbstractPersonalTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    /**
     * Set translations
     *
     * @param ArrayCollection [AbstractPersonalTranslation] $translations
     */
    public function setTranslations($translations)
    {
        foreach ($translations as $translation) {
            $translation->setObject($this);
        }

        $this->translations = $translations;
    }
}
