<?php

namespace TunaCMS\Bundle\MenuBundle\Sluggable\Handler;

use Gedmo\Exception\InvalidArgumentException;
use Gedmo\Sluggable\Handler\TreeSlugHandler;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Model\SluggableInterface;

class MenuSlugHandler extends TreeSlugHandler
{
    /**
     * {@inheritDoc}
     */
    public function transliterate($text, $separator, $object)
    {
        if (!$object instanceof MenuInterface) {
            return new InvalidArgumentException(sprintf('Invalid type for $object. Expected NodeInterface, but got %s', get_class($object)));
        }

        if ($object->getSlug() === '') {
            return '';
        }

        if (!$object instanceof SluggableInterface) {
            return null;
        }

        return parent::transliterate($text, $separator, $object);
    }
}
