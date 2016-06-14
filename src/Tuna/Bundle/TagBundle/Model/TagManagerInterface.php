<?php

namespace TheCodeine\TagBundle\Model;

interface TagManagerInterface
{
    /**
     * Create empty tag
     *
     * @return TheCodeine\TagBundle\Entity\Tag
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