<?php

namespace TheCodeine\TranslationBundle;

use Lexik\Bundle\TranslationBundle\LexikTranslationBundle;

class TheCodeineTranslationBundle extends LexikTranslationBundle
{
    public function getParent()
    {
        return 'LexikTranslationBundle';
    }
}
