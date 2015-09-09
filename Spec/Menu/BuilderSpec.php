<?php

namespace Menu\TheCodeine\AdminBundle\Menu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Knp\Menu\FactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BuilderSpec extends ObjectBehavior
{

    function let(FactoryInterface $factoryInterface, ObjectManager $objectManager)
    {
        $this->beConstructedWith($factoryInterface, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Menu\Builder');
    }
}
