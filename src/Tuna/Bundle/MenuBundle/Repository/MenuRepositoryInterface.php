<?php

namespace TunaCMS\Bundle\MenuBundle\Repository;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

interface MenuRepositoryInterface
{
    /**
     * @return MenuInterface[]
     */
    public function getMenuRoots();

    /**
     * @param MenuInterface $node
     *
     * @return MenuInterface
     */
    public function loadPublishedNodeTree(MenuInterface $node);

    /**
     * @param MenuInterface $node
     *
     * @return MenuInterface
     */
    public function loadWholeNodeTree(MenuInterface $node);

    /**
     * @param MenuInterface $node
     * @param $locale
     * @param $defaultLocale
     *
     * @return MenuInterface
     */
    public function loadPublishedNodeTreeForLocale(MenuInterface $node, $locale, $defaultLocale);

    /**
     * @param MenuInterface $node
     * @param $locale
     * @param $defaultLocale
     *
     * @return MenuInterface
     */
    public function loadWholeNodeTreeForLocale(MenuInterface $node, $locale, $defaultLocale);
}
