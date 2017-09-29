<?php

namespace TunaCMS\Bundle\MenuBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Gedmo\Tree\RepositoryInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

/**
 * Interface MenuRepositoryInterface
 */
interface MenuRepositoryInterface extends RepositoryInterface, ObjectRepository
{
    /**
     * @return MenuInterface[]
     */
    public function getRoots();

    /**
     * @param MenuInterface $menu
     *
     * @return MenuInterface
     */
    public function loadPublishedTree(MenuInterface $menu);

    /**
     * @param MenuInterface $menu
     *
     * @return MenuInterface
     */
    public function loadWholeTree(MenuInterface $menu);
}
