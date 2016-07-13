<?php

namespace TheCodeine\AdminBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractComponentVoter extends AbstractVoter
{
    const CREATE = 'create';
    const DELETE = 'delete';

    protected function getSupportedClasses()
    {
        return array(self::CREATE, self::DELETE);
    }

    protected function isGranted($attribute, $object, $user = null)
    {
        if (!$user instanceof UserInterface) {
            return false;
        }


        switch ($attribute) {
            case self::CREATE:
                break;
            case self::DELETE:
                break;
        }

        return false;
    }
}
