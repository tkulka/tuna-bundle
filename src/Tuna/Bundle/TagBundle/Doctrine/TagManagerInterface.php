<?php

namespace TunaCMS\Bundle\TagBundle\Doctrine;

use TunaCMS\Bundle\TagBundle\Entity\Tag;

interface TagManagerInterface
{
    /**
     * Create empty tag
     *
     * @return Tag
     */
    public function createTag();

    /**
     * Find tags by its names.
     *
     * @param array $tagNames
     *
     * @return \Traversable
     */
    public function findTagsByName(array $tagNames);
}
