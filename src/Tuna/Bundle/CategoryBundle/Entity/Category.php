<?php

namespace TheCodeine\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="categories")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @Gedmo\TranslationEntity(class="TheCodeine\CategoryBundle\Entity\CategoryTranslation")
 * @UniqueEntity("name")
 * @ORM\Entity
 *
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CategoryTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return ArrayCollection|CategoryTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(CategoryTranslation $translation)
    {
        if (!$this->translations->contains($translation) && $translation->getContent()) {
            $this->translations[] = $translation;
            $translation->setObject($this);
        }

        return $this;
    }

    public function removeTranslation(CategoryTranslation $translation)
    {
        $this->translations->remove($translation);

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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

}
