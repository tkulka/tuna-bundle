<?php

namespace Controller\TheCodeine\GalleryBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GalleryControllerSpec extends ObjectBehavior
{
    function let(ContainerInterface $containerInterface)
    {
        $this->setContainer($containerInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\GalleryBundle\Controller\GalleryController');
    }
}
