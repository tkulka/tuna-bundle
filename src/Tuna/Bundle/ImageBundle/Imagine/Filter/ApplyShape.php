<?php

namespace TheCodeine\ImageBundle\Imagine\Filter;

use Imagine\Exception\InvalidArgumentException;

use Imagine\Imagick\Image;

use Imagine\Image\Color;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;

class ApplyShape implements FilterInterface
{
    /**
     * @var ImageInterface
     */
    private $shapeMask;

    /**
     * @param ImageInterface $shapeMask
     */
    public function __construct(ImageInterface $shapeMask)
    {
        $this->shapeMask = $shapeMask;
    }

    /**
     * (non-PHPdoc)
     * @see \Imagine\Filter\FilterInterface::apply()
     */
    public function apply(ImageInterface $image)
    {
        if (!$image instanceof Image) {
            throw new InvalidArgumentException("This filter is supported by Image Magick driver only!");
        }
        return $image->applyMask($this->shapeMask)->removeTransparency(new Color('fff'));
    }
}
