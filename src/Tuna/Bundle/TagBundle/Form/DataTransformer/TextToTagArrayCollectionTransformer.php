<?php

namespace TheCodeine\TagBundle\Form\DataTransformer;

use TheCodeine\TagBundle\Doctrine\TagManager;

use TheCodeine\TagBundle\Model\TagManagerInterface;

use TheCodeine\TagBundle\Entity\Tag;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\PersistentCollection;

class TextToTagArrayCollectionTransformer implements DataTransformerInterface
{
    /**
     * Tag manager instance
     *
     * @var TagManagerInterface
     */
    private $tagManager;

    public function __construct(TagManagerInterface $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($value)
    {
        if (null === $value) {
            return new ArrayCollection();
        }

        if (!$value instanceof ArrayCollection && !$value instanceof PersistentCollection) {
            throw new TransformationFailedException('Expected an object of Doctrine\Common\Collections\ArrayCollection or Doctrine\ORM\PersistentCollection type.');
        }

        return implode(',', array_map(function (Tag $tag) {
            return mb_strtolower($tag->getName(), mb_detect_encoding($tag->getName()));
        }, $value->toArray()));
    }

    public function reverseTransform($value)
    {
        $tagCollection = new ArrayCollection();

        if (!$value) {
            return $tagCollection;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        $tagNames = array_map(function ($tagName) {
            return \trim($tagName);
        }, explode(',', \trim(mb_strtolower($value, mb_detect_encoding($value)))));

        $tagsExist = $this->tagManager->findTagsByName($tagNames);

        foreach ($tagsExist as $tag) {
            $tagCollection->add($tag);
        }

        $tagsNew = array_diff($tagNames, array_map(function (Tag $tag) {
            return mb_strtolower($tag->getName(), mb_detect_encoding($tag->getName()));
        }, $tagsExist));
        foreach ($tagsNew as $tagName) {
            $tag = $this->tagManager->createTag();
            $tag->setName(\trim($tagName));

            $tagCollection->add($tag);
        }

        return $tagCollection;
    }
}
