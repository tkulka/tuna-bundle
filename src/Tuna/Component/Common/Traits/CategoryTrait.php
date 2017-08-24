<?php

namespace TunaCMS\CommonComponent\Traits;

use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\CategoryBundle\Entity\Category;

trait CategoryTrait
{
    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\CategoryBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;

    /**
     * @inheritdoc
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return $this->category;
    }
}
