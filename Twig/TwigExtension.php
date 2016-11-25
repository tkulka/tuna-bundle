<?php

namespace TheCodeine\AdminBundle\Twig;

use Symfony\Component\Intl\Intl;

class TwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'thecodeine_admin_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('country_name', [$this, 'countryName']),
        ];
    }

    public function countryName($locale)
    {
        return Intl::getLocaleBundle()->getLocaleName($locale);
    }
}
