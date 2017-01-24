<?php

namespace Form\TheCodeine\ImageBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Bridge\Doctrine\RegistryInterface;

class ImageIdTypeSpec extends ObjectBehavior
{

    function let(RegistryInterface $registryInterface)
    {
        $this->beConstructedWith($registryInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\ImageBundle\Form\ImageIdType');
    }
}
