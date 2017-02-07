<?php

namespace TheCodeine\NewsBundle\Service;

use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\AbstractNews;
use TheCodeine\NewsBundle\Entity\Event;
use TheCodeine\NewsBundle\Form\NewsType;
use TheCodeine\NewsBundle\Form\EventType;

class NewsFactory
{
    /**
     * @param string|null $type
     *
     * @return Event|News
     */
    public function getInstance($type = null)
    {
        switch (ucfirst(strtolower($type))) {
            case 'Event':
                return new Event();
            default:
                return new News();
        }
    }

    /**
     * @param AbstractNews|null $abstractNews
     *
     * @return EventType|NewsType
     */
    public function getFormInstance(AbstractNews $abstractNews = null)
    {
        switch (true) {
            case $abstractNews instanceof Event:
                return new EventType();
            default:
                return new NewsType();
        }
    }
}