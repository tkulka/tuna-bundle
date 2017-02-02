<?php

namespace TheCodeine\MenuBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TheCodeine\MenuBundle\Entity\Menu;

class CreateMenuCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('tuna:menu:create')
            ->setDescription('Create new menu.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the menu.')
            ->setHelp('This command allows you to create menu root items, that you can later manage in tuna admin.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $menu = new Menu($input->getArgument('name'));
        $em->persist($menu);
        $em->flush();

        $output->writeln('Created new menu item.');
    }
}
