<?php

namespace TheCodeine\ImageBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;

use TheCodeine\ImageBundle\Entity\Image;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\DataTransformerInterface;

class IdToImageTransformer implements DataTransformerInterface
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function transform($image)
    {
        if (!$image instanceof Image) {
            return "";
        }

        return $image->getId();
    }

    public function reverseTransform($id)
    {
        if (null === $id) {
            return null;
        }

        $image = $this->doctrine->getRepository('TheCodeineImageBundle:Image')->findOneById((int)$id);
        if (null === $image) {
            throw new TransformationFailedException(sprintf("An image with id `%s` doesn't exists", $id));
        }

        return $image;
    }
}
