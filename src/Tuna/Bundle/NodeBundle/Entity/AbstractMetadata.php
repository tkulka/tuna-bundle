<?php

namespace TunaCMS\Bundle\NodeBundle\Entity;

use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;
use TunaCMS\Bundle\NodeBundle\Traits\MetadataTrait;
use TunaCMS\Component\Common\Traits\IdTrait;

class AbstractMetadata implements MetadataInterface
{
    use IdTrait;
    use MetadataTrait;

    public function __construct()
    {
        $this->setIndexable(true);
    }
}
