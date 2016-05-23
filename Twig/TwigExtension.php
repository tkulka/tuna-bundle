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
        return array(
            'country_name' => new \Twig_Function_Method($this, 'countryName'),
        );
    }

    public function countryName($locale)
    {
        return Intl::getLocaleBundle()->getLocaleName($locale);
    }
}
