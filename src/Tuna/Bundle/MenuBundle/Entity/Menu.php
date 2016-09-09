<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TheCodeine\PageBundle\Entity\Page;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="MenuRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"TheCodeine\MenuBundle\EventListener\MenuListener"})
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $externalUrl;

    /**
     * @var string
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="TheCodeine\MenuBundle\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"path"})
     * @ORM\Column(type="string", length=255, nullable=true)
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
    public function __construct($label = null)
    {
        $this->setLabel($label);
        $this->setClickable(false);
        $this->setPublished(false);

        $this->translations = new ArrayCollection();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!$this->getPage() && $this->isClickable() && !$this->getPath() && !$this->getExternalUrl()) {
            $context->buildViolation('You must provide path or external url.')
                ->atPath('path')
                ->addViolation();
        }
    }

    public function setTreeData($lft, $rgt, $lvl, Menu $parent = null)
    {
        $this->lft = $lft;
        $this->rgt = $rgt;
        $this->lvl = $lvl;
        $this->parent = $parent;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getIndentedName()
    {
        return str_repeat("--", $this->lvl) . ' ' . $this->getLabel();
    }

    public function getParentId()
    {
        return $this->getParent() ? $this->getParent()->getId() : null;
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

    /**
     * @param Menu|null $parent
     */
    public function setParent(Menu $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return Menu|null
     */
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
        $this->path = trim($path);

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

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    /**
     * @return $this
     * @param string $externalUrl
     */
    public function setExternalUrl($externalUrl)
    {
        $this->externalUrl = trim($externalUrl);

        return $this;
    }
}
