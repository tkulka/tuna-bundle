<?php

namespace TheCodeine\TranslationBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

class CacheController extends Controller
{
    /**
     * @Route("/refresh")
     *
     * @return Response
     */
    public function clearCache()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));

        return new Response();
    }
}
