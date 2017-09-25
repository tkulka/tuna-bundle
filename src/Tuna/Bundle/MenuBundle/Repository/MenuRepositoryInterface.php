<?php

namespace TunaCMS\Bundle\MenuBundle\Repository;

use Gedmo\Tree\RepositoryInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

interface MenuRepositoryInterface extends RepositoryInterface
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
