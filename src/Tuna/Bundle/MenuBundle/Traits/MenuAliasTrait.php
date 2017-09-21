<?php

namespace TunaCMS\Bundle\MenuBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

trait MenuAliasTrait
{
    /**
     * @var MenuInterface
     *
     * @ORM\ManyToOne(targetEntity="TunaCMS\Bundle\MenuBundle\Model\MenuInterface")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $targetMenu;

    /**
     * @return MenuInterface
     */
    public function getTargetMenu()
    {
        return $this->targetMenu;
    }

    /**
     * @return $this
     *
     * @param MenuInterface $targetMenu
     */
    public function setTargetMenu(MenuInterface $targetMenu = null)
    {
        $this->targetMenu = $targetMenu;

        return $this;
    }
}
