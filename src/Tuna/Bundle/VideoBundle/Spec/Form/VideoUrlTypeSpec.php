<?php

namespace Form\TheCodeine\VideoBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use TheCodeine\VideoBundle\Model\VideoManagerInterface;

class VideoUrlTypeSpec extends ObjectBehavior
{
    function let(VideoManagerInterface $videoManagerInterface)
    {
        $this->beConstructedWith($videoManagerInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\VideoBundle\Form\VideoUrlType');
    }
}
