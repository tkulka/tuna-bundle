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
     * Image manager instance
     *
     * @var ImageManagerInterface
     */
    private $imageManager;

    public static function getSubscribingMethods()
    {
        $methods = array();

        foreach (array('json') as $format) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => 'TheCodeine\ImageBundle\Entity\Image',
                'format' => $format,
            );
        }

        return $methods;
    }

    /**
     * @param ImageManagerInterface $imageManager
     */
    public function __construct(ImageManagerInterface $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function serializeImageToJson(JsonSerializationVisitor $visitor, Image $image, array $type, Context $context)
    {
        $type['name'] = 'array';

        return $visitor->visitArray(array('id' => $image->getId(), 'url' => $this->imageManager->filePathToWebPath($image->getPath())), $type, $context);
    }
}