<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
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
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setPlainPassword('admin');
        $user->setUsername('admin');
        $user->setEmail('fakeadmin@thecodeine.com');
        $user->addRole('ROLE_ADMIN');

        $userManager->updateUser($user);

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setPlainPassword('superadmin');
        $user->setUsername('superadmin');
        $user->setEmail('fakesuperadmin@thecodeine.com');
        $user->addRole('ROLE_SUPER_ADMIN');

        $userManager->updateUser($user);
    }


    public function getOrder()
    {
        return 12;
    }

}