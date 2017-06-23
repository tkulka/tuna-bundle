<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use TunaCMS\PageComponent\Model\PageInterface;

interface MenuInterface
{
    /**
     * @param $lft
     * @param $rgt
     * @param $lvl
     * @param MenuInterface|null $parent
     *
     * @return $this
     */
    public function setTreeData($lft, $rgt, $lvl, MenuInterface $parent = null);

    public function getIndentedName();

    public function getParentId();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return MenuInterface|null
     */
    public function getRoot();

    /**
     * @param MenuInterface|null $parent
     *
     * @return $this
     */
    public function setParent(MenuInterface $parent = null);

    /**
     * @return MenuInterface|null
     */
    public function getParent();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return $this
     *
     * @param string $label
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return $this
     *
     * @param string $slug
     */
    public function setSlug($slug);

    /**
     * @return PageInterface
     */
    public function getPage();

    /**
     * @param PageInterface $pageInterface
     *
     * @return self
     */
    public function setPage(PageInterface $pageInterface = null);

    /**
     * @return boolean
     */
    public function isClickable();

    /**
     * @return $this
     *
     * @param boolean $clickable
     */
    public function setClickable($clickable);

    /**
     * @return \DateTime
     */
    public function getPublishDate();

    /**
     * @return $this
     *
     * @param \DateTime $publishDate
     */
    public function setPublishDate(\DateTime $publishDate = null);

    /**
     * @param boolean $published
     *
     * @return MenuInterface
     */
    public function setPublished($published);

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return $this
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * @return ArrayCollection|AbstractPersonalTranslation[]
     */
    public function getTranslations();

    /**
     * @param AbstractPersonalTranslation $translation
     *
     * @return $this
     */
    public function addTranslation(AbstractPersonalTranslation $translation);

    /**
     * @param AbstractPersonalTranslation $t
     *
     * @return $this
     */
    public function removeTranslation(AbstractPersonalTranslation $t);

    /**
     * @param ArrayCollection $translations
     *
     * @return $this
     */
    public function setTranslations(ArrayCollection $translations);

    /**
     * @return ArrayCollection|MenuInterface[]|null
     */
    public function getChildren();

    /**
     * @param ArrayCollection|null $children
     *
     * @return $this
     */
    public function setChildren(ArrayCollection $children = null);

    /**
     * @return mixed
     */
    public function getLocale();

    /**
     * @return string
     */
    public function getExternalUrl();

    /**
     * @return $this
     *
     * @param string $externalUrl
     */
    public function setExternalUrl($externalUrl);

    /**
     * @return int
     */
    public function getLft();

    /**
     * @return int
     */
    public function getLvl();

    /**
     * @return int
     */
    public function getRgt();
}
