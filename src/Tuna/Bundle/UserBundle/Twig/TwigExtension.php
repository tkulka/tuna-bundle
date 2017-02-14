<?php

namespace TheCodeine\UserBundle\Twig;

class TwigExtension extends \Twig_Extension
{

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('generate_core_url', [$this, 'generateCode']),
        ];
    }

    public function generateCode($user)
    {
        return $this->provider->getUrl($user);
    }

    public function getName()
    {
        return 'app_extension';
    }
}
