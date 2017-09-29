<?php

namespace TunaCMS\Bundle\MenuBundle\Sluggable\Handler;

use Gedmo\Exception\InvalidArgumentException;
use Gedmo\Sluggable\Handler\TreeSlugHandler;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

class MenuSlugHandler extends TreeSlugHandler
{
    /**
     * {@inheritDoc}
     */
    public function transliterate($text, $separator, $object)
    {
        if (!$object instanceof MenuInterface) {
            return new InvalidArgumentException(sprintf(
                'Expected argument of type "%s", "%s" given.', MenuInterface::class, get_class($object)
            ));
        }

        if ($object->getSlug() === '') {
            return '';
        }

        if ($object->isEmptySlug()) {
            return null;
        }

        return parent::transliterate($text, $separator, $object);
    }
}
