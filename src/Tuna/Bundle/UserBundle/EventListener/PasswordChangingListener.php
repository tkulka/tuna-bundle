<?php

namespace TheCodeine\UserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordChangingListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onPasswordChangingSuccess',
        );
    }

    public function onPasswordChangingSuccess(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_change_password');

        $event->setResponse(new RedirectResponse($url));
    }
}
