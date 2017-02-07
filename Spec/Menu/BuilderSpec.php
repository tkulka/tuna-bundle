<?php

namespace Menu\TheCodeine\AdminBundle\Menu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Knp\Menu\FactoryInterface;

class BuilderSpec extends ObjectBehavior
{

    function let(FactoryInterface $factoryInterface)
    {
        $this->beConstructedWith($factoryInterface, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Menu\Builder');
    }
}
