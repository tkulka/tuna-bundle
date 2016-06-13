<?php

namespace TheCodeine\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TheCodeineUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
