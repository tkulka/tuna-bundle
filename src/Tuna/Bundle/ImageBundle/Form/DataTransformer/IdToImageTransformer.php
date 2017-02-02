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
    private $registryInterface;

    public function __construct(RegistryInterface $registryInterface)
    {
        $this->registryInterface = $registryInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($image)
    {
        if (!$image instanceof Image) {
            return "";
        }

        return $image->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($id)
    {
        if (null === $id) {
            return null;
        }

        $image = $this->registryInterface->getRepository(Image::class)->find($id);

        if (null === $image) {
            throw new TransformationFailedException(sprintf("An image with id `%s` doesn't exists", $id));
        }

        return $image;
    }
}