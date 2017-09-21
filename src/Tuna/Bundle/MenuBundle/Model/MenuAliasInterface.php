<?php

namespace TunaCMS\Bundle\MenuBundle\Model;

interface MenuAliasInterface extends MenuInterface
{
    /**
     * @return MenuInterface
     */
    public function getTargetMenu();

    /**
     * @param MenuInterface $menu
     *
     * @return $this
     */
    public function setTargetMenu(MenuInterface $menu = null);
}
