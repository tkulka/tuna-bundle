<?php

namespace TheCodeine\MenuBundle\Sluggable\Handler;

use Gedmo\Sluggable\Handler\TreeSlugHandler as BaseTreeSlugHandler;

class TreeSlugHandler extends BaseTreeSlugHandler
{
    /**
     * {@inheritDoc}
     */
    public function transliterate($text, $separator, $object)
    {
        $slug = parent::transliterate($text, $separator, $object);

        if ($text === null && !$slug) {
            $slug = null;
        }

        return $slug;
    }
}