<?php

namespace TheCodeine\ImageBundle\Imagine\Filter;

use Imagine\Image\Point;

use Imagine\Image\ImageInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use Imagine\Filter\FilterInterface;

class SmartResize implements FilterInterface
{
    /**
     * @var BoxInterface
     */
    private $size;

    /**
     * @param BoxInterface $size
     */
    public function __construct(BoxInterface $size)
    {
        $this->size = $size;
    }

    /**
     * (non-PHPdoc)
     * @see \Imagine\Filter\FilterInterface::apply()
     */
    public function apply(ImageInterface $image)
    {
        $size = $this->getBoundingBox($image);
        $start = $this->getStartPoint($size);

        $image->resize($size);
        $image->crop($start, $this->size);

        return $image;
    }

    /**
     * Get bounding box
     *
     * @param ImageInterface $image
     * @return BoxInterface
     */
    private function getBoundingBox(ImageInterface $image)
    {
        $box = $image->getSize()->widen($this->size->getWidth());
        if ($box->getHeight() >= $this->size->getHeight()) {
            return $box;
        }

        return $image->getSize()->heighten($this->size->getHeight());
    }

    /**
     * Get starting point that size box is centered
     *
     * @param BoxInterface $boundingBox
     * @return PointInterface
     */
    private function getStartPoint(BoxInterface $boundingBox)
    {
        $x = (int) round(0.5*($boundingBox->getWidth() - $this->size->getWidth()));
        $y = (int) round(0.5*($boundingBox->getHeight() - $this->size->getHeight()));

        return new Point($x, $y);
    }
}
