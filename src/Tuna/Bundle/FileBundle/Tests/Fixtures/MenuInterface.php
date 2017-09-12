<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Fixtures;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface as BaseMenuInterface;
use TunaCMS\CommonComponent\Model\SlugInterface;

/**
 * todo remove when MenuInterface (BaseMenuInterface) will support getSlug method
 */
interface MenuInterface extends BaseMenuInterface, SlugInterface
{

}