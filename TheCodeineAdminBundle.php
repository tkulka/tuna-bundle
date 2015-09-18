<?php

namespace TheCodeine\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TheCodeineAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
