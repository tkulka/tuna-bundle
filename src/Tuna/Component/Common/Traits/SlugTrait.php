<?php

namespace TunaCMS\CommonComponent\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SlugTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @inheritdoc
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSlug()
    {
        return $this->slug;
    }
}