<?php

namespace TunaCMS\Bundle\NodeBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use TunaCMS\Bundle\NodeBundle\Entity\NodeTranslation;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Sluggable\NodeTranslationManager;

class NodeTranslationSubscriber implements EventSubscriber
{
    private $updatedTranslations = [];

    private $removedTranslations = [];

    /**
     * @var NodeTranslationManager
     */
    private $slugManager;

    public function __construct(NodeTranslationManager $slugTranslationManager)
    {
        $this->slugManager = $slugTranslationManager;
    }

    public function getSubscribedEvents()
    {
        return [
            'preUpdate',
            'prePersist',
            'postFlush',
            'preRemove',
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $slugSourceTranslation = $this->extractSlugSourceTranslation($args);

        if (!$slugSourceTranslation || !$slugSourceTranslation->getContent()) {
            return;
        }

        $this->updatedTranslations[] = $this->slugManager->generateSlugTranslation($slugSourceTranslation);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $slugSourceTranslation = $this->extractSlugSourceTranslation($args);

        if (!$slugSourceTranslation || !$slugSourceTranslation->getContent()) {
            return;
        }

        $this->updatedTranslations[] = $this->slugManager->generateSlugTranslation($slugSourceTranslation);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $slugSourceTranslation = $this->extractSlugSourceTranslation($args);

        if (!$slugSourceTranslation) {
            return;
        }

        /* @var $node NodeInterface */
        $node = $slugSourceTranslation->getObject();
        foreach ($node->getTranslations() as $translation) {
            if ($translation->getLocale() == $slugSourceTranslation->getLocale() && $translation->getField() == NodeTranslationManager::FIELD_SLUG) {
                $this->removedTranslations[] = $translation;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->flushChangedTranslations($args->getEntityManager());
    }

    protected function flushChangedTranslations(EntityManagerInterface $em)
    {
        if ($this->removedTranslations) {
            foreach ($this->removedTranslations as $translation) {
                $em->remove($translation);
            }
            $this->removedTranslations = [];
            $em->flush();
        }

        $roots = $this->updatedTranslations;
        if ($this->updatedTranslations) {
            foreach ($this->updatedTranslations as $translation) {
                $em->persist($translation);
            }
            $this->updatedTranslations = [];
            $em->flush();
        }

        foreach ($roots as $root) {
            $this->slugManager->regenerateSlugTranslations($root->getObject(), $root->getLocale());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return null|NodeTranslation
     */
    protected function extractSlugSourceTranslation(LifecycleEventArgs $args)
    {
        $translation = $args->getEntity();
        if (!($translation instanceof NodeTranslation) || $translation->getField() !== NodeTranslationManager::FIELD_SLUG_SOURCE) {
            return null;
        }

        return $translation;
    }
}
