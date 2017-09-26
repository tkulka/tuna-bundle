<?php

namespace TunaCMS\Component\Common\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TunaCMS\Bundle\FileBundle\Entity\Image;

trait ImageTrait
{
    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="TunaCMS\Bundle\FileBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Assert\Valid
     */
    protected $image;

    /**
     * @inheritdoc
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImage()
    {
        return $this->image;
    }
}
