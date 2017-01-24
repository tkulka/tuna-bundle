<?php

namespace Form\TheCodeine\ImageBundle\Form\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ImageListenerSpec extends ObjectBehavior
{

    function let(RegistryInterface $registryInterface, FormFactoryInterface $formFactoryInterface)
    {
        $this->beConstructedWith($registryInterface, $formFactoryInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Form\EventListener\ImageListener');
    }
}
