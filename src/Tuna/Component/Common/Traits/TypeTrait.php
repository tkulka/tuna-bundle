<?php

namespace TunaCMS\Component\Common\Traits;

trait TypeTrait
{
    /**
     * @inheritdoc
     */
    public function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
