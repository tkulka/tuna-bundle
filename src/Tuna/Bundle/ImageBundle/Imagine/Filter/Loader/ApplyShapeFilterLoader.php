<?php

namespace TheCodeine\ImageBundle\Imagine\Filter\Loader;

use Imagine\Image\ImagineInterface;
use Imagine\Image\ImageInterface;
use Imagine\Exception\InvalidArgumentException;

use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

use TheCodeine\ImageBundle\Imagine\Filter\ApplyShape;

class ApplyShapeFilterLoader implements LoaderInterface
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
     */
    function load(ImageInterface $image, array $options = array())
    {
        if (!array_key_exists('file', $options)) {
            throw new InvalidArgumentException('Expected mask file path, none given');
        }

        if (!file_exists($options['file'])) {
            throw new InvalidArgumentException("Mask file doesn't exists");
        }

        $mask = new ApplyShape($this->imagine->open($options['file']));

        return $mask->apply($image);
    }
}