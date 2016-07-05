<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\PageBundle\Entity\AbstractPage;

/**
 * AbstractNews
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Entity\NewsRepository")
 * @Gedmo\TranslationEntity(class="TheCodeine\NewsBundle\Entity\NewsTranslation")
 *
 * @ORM\Table(name="news")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="news_type", type="string")
 *
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractNews extends AbstractPage
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

    public function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
