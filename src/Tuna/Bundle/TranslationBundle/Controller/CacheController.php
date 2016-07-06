<?php

namespace TheCodeine\TranslationBundle\Controller;

use JMS\TranslationBundle\Controller\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

class CacheController extends ApiController
{
    /**
     * @Route("/translations/save")
     */
    public function clearCache()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
    }
}
