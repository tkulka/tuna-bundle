<?php

namespace TheCodeine\AdminBundle\Twig;

use Symfony\Component\Intl\Intl;

class TwigExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('country_name', [$this, 'countryName']),
        ];
    }

    /**
     * @param $locale
     * @param null $displayLocale
     *
     * @return null|string
     */
    public function countryName($locale, $displayLocale = null)
    {
        return Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale);
    }
}
