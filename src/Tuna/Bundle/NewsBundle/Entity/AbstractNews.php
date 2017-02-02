<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\PageBundle\Entity\AbstractPage;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodeine\TagBundle\Entity\Tag;

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
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="NewsTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Assert\Valid
     *
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
     * AbstractNews constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection();

        $this->setImportant(false);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return AbstractNews
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Tag $tag
     *
     * @return AbstractNews
     */
    public function addTag(Tag $tag)
    {

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param boolean $important
     *
     * @return AbstractNews
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isImportant()
    {
        return $this->important;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
