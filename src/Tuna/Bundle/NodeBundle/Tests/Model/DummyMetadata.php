<?php

namespace TunaCMS\Bundle\NodeBundle\Tests\Model;

use TunaCMS\Bundle\NodeBundle\Model\MetadataInterface;
use TunaCMS\Bundle\NodeBundle\Traits\MetadataTrait;

class DummyMetadata implements MetadataInterface
{
    use MetadataTrait;
}