<?php

namespace TunaCMS\Component\Common\Model;

interface AliasInterface
{
    /**
     * @param string $alias
     *
     * @return self
     */
    public function setAlias($alias);

    /**
     * @return string
     */
    public function getAlias();
}
