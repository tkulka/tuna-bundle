<?php

namespace TunaCMS\Component\Common\Model;

use TunaCMS\Bundle\CategoryBundle\Entity\Category;

interface CategoryInterface
{
    /**
     * @param Category $category
     *
     * @return self
     */
    public function setCategory(Category $category = null);

    /**
     * @return Category
     */
    public function getCategory();
}
