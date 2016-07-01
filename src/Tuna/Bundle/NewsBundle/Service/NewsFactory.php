<?php

namespace TheCodeine\NewsBundle\Service;

use Doctrine\ORM\EntityManager;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\AbstractNews;
use TheCodeine\NewsBundle\Entity\Event;
use TheCodeine\NewsBundle\Form\NewsType;
use TheCodeine\NewsBundle\Form\EventType;

class NewsFactory
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getInstance($type = null)
    {
        $type = ucfirst(strtolower($type));
        switch ($type) {
            case 'Event':
                return new Event();
            default:
                return new News();
        }
    }

    public function getFormInstance(AbstractNews $news = null, $validate = true)
    {
        switch (true) {
            case $news instanceof Event:
                return new EventType($validate);
            default:
                return new NewsType($validate);
        }
    }
}
