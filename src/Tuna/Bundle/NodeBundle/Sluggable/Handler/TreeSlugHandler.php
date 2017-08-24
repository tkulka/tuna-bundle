<?php

namespace TunaCMS\Bundle\NodeBundle\Sluggable\Handler;

use Gedmo\Sluggable\Handler\TreeSlugHandler as BaseTreeSlugHandler;

class TreeSlugHandler extends BaseTreeSlugHandler
{
    /**
     * {@inheritDoc}
     */
    public function transliterate($text, $separator, $object)
    {
        if ($object->getSlug() === '') {
            // allow slug to be explicitly set to an empty string (mainly for homepages `/`)
            return '';
        }

        $slug = parent::transliterate($text, $separator, $object);

        if ($object->isRootOfATree()) {
            $slug = null;
        }

        return $slug;
    }
}
