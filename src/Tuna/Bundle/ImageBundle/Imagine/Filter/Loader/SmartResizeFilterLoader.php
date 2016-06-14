<?php

namespace TheCodeine\ImageBundle\Imagine\Filter\Loader;

use Imagine\Image\ImagineInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Filter\FilterInterface;
use Imagine\Filter\Basic\ApplyMask;

use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

use TheCodeine\ImageBundle\Imagine\Filter\SmartResize;

class SmartResizeFilterLoader implements LoaderInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @param ImagineInterface $imagine
     */
    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * @param ImageInterface $image
     * @param array $options
     *
     * @return ImageInterface
     */
    function load(ImageInterface $image, array $options = array())
    {
        list($width, $height) = $options['size'];

        $filter = new SmartResize(new Box($width, $height));
        $image = $filter->apply($image);

        return $image;
    }

}