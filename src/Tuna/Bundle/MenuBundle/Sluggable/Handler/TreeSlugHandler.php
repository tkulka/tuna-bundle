<?php

namespace TheCodeine\MenuBundle\Sluggable\Handler;

class TreeSlugHandler extends \Gedmo\Sluggable\Handler\TreeSlugHandler
{
    public function transliterate($text, $separator, $object)
    {
        $slug = parent::transliterate($text, $separator, $object);

        if ($text === null && !$slug) {
            $slug = null;
        }

        return $slug;
    }
}
