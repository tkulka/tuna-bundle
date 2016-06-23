<?php

namespace TheCodeine\TranslationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TheCodeineTranslationBundle extends Bundle
{
    public function getParent()
    {
        return 'JMSTranslationBundle';
    }
}
