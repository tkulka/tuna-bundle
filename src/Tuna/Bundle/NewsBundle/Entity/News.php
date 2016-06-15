<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\PageBundle\Entity\BasePage;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Repository\NewsRepository")
 * @Gedmo\TranslationEntity(class="TheCodeine\NewsBundle\Entity\NewsTranslation")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="news_type", type="string")
 *
 * @ORM\HasLifecycleCallbacks
 */
class News extends BasePage
{
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="NewsTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @ORM\ManyToMany(targetEntity="Attachment", cascade={"persist"})
     * @ORM\JoinTable(name="news_attachments",
     *      joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true)}
     *      )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $attachments;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="NewsCategory")
     * @ORM\JoinColumn(name="news_category_id", referencedColumnName="id")
     */
    protected $newsCategory;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="\TheCodeine\TagBundle\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="news_tags",
     *      joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $important;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection();
        $this->setImportant(false);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Set created
     *
     * @param \DateTime $createdAt
     * @return News
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get category
     *
     * @return \TheCodeine\NewsBundle\Entity\NewsCategory
     */
    public function getNewsCategory()
    {
        return $this->newsCategory;
    }

    /**
     * Add tags
     *
     * @param \TheCodeine\TagBundle\Entity\Tag $tag
     * @return News
     */
    public function addTag(\TheCodeine\TagBundle\Entity\Tag $tag)
    {

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \TheCodeine\TagBundle\Entity\Tag $tag
     */
    public function removeTag(\TheCodeine\TagBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set important flag
     *
     * @param boolean $important
     * @return News
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }


    /**
     * Get important flag
     *
     * @return boolean
     */
    public function isImportant()
    {
        return $this->important;
    }

}
