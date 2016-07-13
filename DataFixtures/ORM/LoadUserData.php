<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use FOS\UserBundle\Entity\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        /* @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');
        $email = 'fake@thecodeine.com';
        $username = 'admin';

        if ($userManager->findUserByUsername($username) == null && $userManager->findUserByEmail($email) == null) {
            $user = $userManager->createUser();
            $user->setEnabled(true);
            $user->setPlainPassword('admin');
            $user->setUsername($username);
            $user->setEmail($email);
            $user->addRole('ROLE_ADMIN');

            $userManager->updateUser($user);
        }
    }


    public function getOrder()
    {
        return 12;
    }

}
