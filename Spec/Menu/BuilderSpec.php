<?php

namespace Menu\TheCodeine\AdminBundle\Menu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Translation\LoggingTranslator;

class BuilderSpec extends ObjectBehavior
{

    function let(FactoryInterface $factoryInterface, LoggingTranslator $loggingTranslator)
    {
        $this->beConstructedWith($factoryInterface, $loggingTranslator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\AdminBundle\Menu\Builder');
    }
}
