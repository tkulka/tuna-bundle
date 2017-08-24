<?php

namespace TunaCMS\Bundle\NodeBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;
use TunaCMS\Bundle\NodeBundle\Sluggable\NodeTranslationManager;

class NodeSubscriber implements EventSubscriber
{
    /**
     * @var NodeTranslationManager
     */
    protected $nodeTranslationManager;

    public function __construct(NodeTranslationManager $nodeTranslationManager)
    {
        $this->nodeTranslationManager = $nodeTranslationManager;
    }

    public function getSubscribedEvents()
    {
        return [
            'preUpdate',
        ];
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $node = $event->getEntity();

        if (!$node instanceof NodeInterface || !$event->hasChangedField('parent')) {
            return;
        }
//        $this->nodeTranslationManager->regenerateSlugTranslations($node);
    }
}
