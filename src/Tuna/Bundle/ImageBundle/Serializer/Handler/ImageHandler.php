<?php

namespace TheCodeine\ImageBundle\Serializer\Handler;

use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\ImageBundle\Model\ImageManagerInterface;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;

class ImageHandler implements SubscribingHandlerInterface
{
    /**
     * @var ImageManagerInterface
     */
    private $imageManager;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];

        foreach (['json'] as $format) {
            $methods[] = [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => 'TheCodeine\ImageBundle\Entity\Image',
                'format' => $format,
            ];
        }

        return $methods;
    }

    /**
     * ImageHandler constructor.
     *
     * @param ImageManagerInterface $imageManager
     */
    public function __construct(ImageManagerInterface $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Image $image
     * @param array $type
     * @param Context $context
     *
     * @return array|\ArrayObject
     */
    public function serializeImageToJson(JsonSerializationVisitor $visitor, Image $image, array $type, Context $context)
    {
        $type['name'] = 'array';

        return $visitor->visitArray([
            'id' => $image->getId(),
            'url' => $this->imageManager->filePathToWebPath($image->getPath())
        ], $type, $context);
    }
}