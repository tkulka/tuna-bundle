<?php

namespace TunaCMS\Bundle\TranslationBundle;

use Lexik\Bundle\TranslationBundle\LexikTranslationBundle;

class TunaCMSTranslationBundle extends LexikTranslationBundle
{
    public function getParent()
    {
        return 'LexikTranslationBundle';
    }
}
