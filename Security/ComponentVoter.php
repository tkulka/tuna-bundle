<?php

namespace TunaCMS\AdminBundle\Security;

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

    /**
     * @var array
     */
    protected $componentsConfig;

    /**
     * ComponentVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     * @param array $componentsConfig
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, array $componentsConfig)
    {
        $this->decisionManager = $decisionManager;
        $this->componentsConfig = $componentsConfig;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::DELETE])) {
            return false;
        }

        return in_array($subject, ['page', 'news', 'events']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN'])) {
            return true;
        }

        return $this->componentsConfig[$subject][$attribute];
    }
}
