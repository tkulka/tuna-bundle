<?php

namespace TunaCMS\Bundle\NodeBundle\Sluggable\Handler;

use Gedmo\Exception\InvalidArgumentException;
use Gedmo\Sluggable\Handler\TreeSlugHandler as BaseTreeSlugHandler;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeSlugHandler extends BaseTreeSlugHandler
{
    /**
     * {@inheritDoc}
     */
    public function transliterate($text, $separator, $object)
    {
        if (!$object instanceof NodeInterface) {
            return new InvalidArgumentException(sprintf('Invalid type for $object. Expected NodeInterface, but got %s', get_class($object)));
        }

        if ($object->isHomepage()) {
            return '';
        }

        if ($object->isRootOfATree()) {
            return null;
        }

        if ($object->isUrlLinkType() || $object->isExternalNodeLinkType()) {
            return null;
        }

        return parent::transliterate($text, $separator, $object);
    }
}
