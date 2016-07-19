<?php

namespace TheCodeine\AdminBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ComponentVoter extends Voter
{
    const CREATE = 'create';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    protected $componentsConfig;

    public function __construct(AccessDecisionManagerInterface $decisionManager, array $componentsConfig)
    {
        $this->decisionManager = $decisionManager;
        $this->componentsConfig = $componentsConfig;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::CREATE, self::DELETE))) {
            return false;
        }

        return in_array($subject, array('pages', 'news', 'events'));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

        return $this->componentsConfig[$subject][$attribute];
    }
}
