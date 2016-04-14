<?php

namespace Menu\TheCodeine\AdminBundle\Menu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class BuilderSpec extends ObjectBehavior
{

    function let(FactoryInterface $factoryInterface, TranslatorInterface $translatorInterface)
    {
        $this->beConstructedWith($factoryInterface, $translatorInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Menu\Builder');
    }
}
