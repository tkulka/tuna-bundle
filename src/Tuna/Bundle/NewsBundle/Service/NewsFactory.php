<?php

namespace TheCodeine\NewsBundle\Service;

use Doctrine\ORM\EntityManager;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\BaseNews;
use TheCodeine\NewsBundle\Entity\Event;
use TheCodeine\NewsBundle\Form\NewsType;
use TheCodeine\NewsBundle\Form\EventType;

class NewsFactory
{

    private static $NEWS_TYPES = array(
        'News' => 'aktualnosci',
        'Event' => 'wydarzenia',
    );

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getInstance($type)
    {
        switch ($type) {
            case 'Event':
                return new Event();
            default:
                return new News();
        }
    }

    public function getFormInstance(BaseNews $news)
    {
        switch ($news instanceof Event) {
            case 'Event':
                return new EventType();
            default:
                return new NewsType();
        }
    }
}
