<?php

namespace TheCodeine\NewsBundle\Service;

use Doctrine\ORM\EntityManager;
use TheCodeine\NewsBundle\Entity\News;
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

    public function getNewsInstance($type)
    {
        switch ($type) {
            case 'News':
                return new News();
            case 'Event':
                return new Event();
        }
    }

    public function getNewsTypeInstance($type)
    {
        switch ($type) {
            case 'News':
                return new NewsType();
            case 'Event':
                return new EventType();
        }
    }

    PUBLIC function getCategoryByNewsType($type)
    {
        return $this->em->getRepository('TheCodeineNewsBundle:Category')->findOneBySlug(self::$NEWS_TYPES[$type]);
    }
}
