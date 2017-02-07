<?php

namespace TheCodeine\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\CategoryBundle\Entity\Category;

/**
 * Page
 *
 * @ORM\Entity(repositoryClass="TheCodeine\PageBundle\Entity\PageRepository")
 */
class CategoryPage extends AbstractPage
{
    /**
     * @ORM\ManyToOne(targetEntity="TheCodeine\CategoryBundle\Entity\Category")
     */
    protected $category;

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return self
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}