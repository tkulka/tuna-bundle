<?php

namespace Form\TheCodeine\ImageBundle\Form\DataTransformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Bridge\Doctrine\RegistryInterface;

class IdToImageTransformerSpec extends ObjectBehavior
{

    function let(RegistryInterface $registryInterface)
    {
        $this->beConstructedWith($registryInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Form\DataTransformer\IdToImageTransformer');
    }
}
