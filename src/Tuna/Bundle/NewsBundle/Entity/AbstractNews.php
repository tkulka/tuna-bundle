<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\FileBundle\Entity\Image;
use TheCodeine\NewsBundle\Model\NewsInterface;
use TheCodeine\TagBundle\Entity\Tag;

/**
 * AbstractNews
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Entity\NewsRepository")
 * @ORM\Table(name="news")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="news_type", type="string")
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\TranslationEntity(class="TheCodeine\NewsBundle\Entity\NewsTranslation")
 */
abstract class AbstractNews implements NewsInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TheCodeine\TagBundle\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="news_tags",
     *      joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     *
     * @Assert\Valid
     */
    protected $tags;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Translatable
     */
    protected $title;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="TheCodeine\FileBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Assert\Valid
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="teaser", type="text", nullable=true)
     *
     * @Gedmo\Translatable
     */
    protected $teaser;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @var Gallery
     *
     * @ORM\OneToOne(targetEntity="TheCodeine\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     *
     * @Assert\Valid
     */
    protected $gallery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $important;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TheCodeine\FileBundle\Entity\Attachment", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true)}
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    protected $attachments;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TheCodeine\NewsBundle\Entity\NewsTranslation", mappedBy="object", cascade={"persist", "remove"})
     *
     * @Assert\Valid
     */
    protected $translations;

    /**
     * AbstractNews constructor.
     *
     * @param null|string $title
     */
    public function __construct($title = null)
    {
        $this->tags = new ArrayCollection();

        $this->setImportant(false);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setGallery(Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * {@inheritdoc}
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * {@inheritdoc}
     */
    public function isImportant()
    {
        return $this->important;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttachments(ArrayCollection $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslations(ArrayCollection $translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * {@inheritdoc}
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        $this->translations->add($translation);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTranslation(AbstractPersonalTranslation $translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}