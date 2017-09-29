<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Sluggable\Handler;

use Gedmo\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Sluggable\Handler\MenuSlugHandler;

class MenuSlugHandlerTest extends TestCase
{
    /**
     * @var MenuSlugHandler
     */
    private $menuSlugHandler;

    protected function setUp()
    {
        $this->menuSlugHandler = $this->getMockBuilder(MenuSlugHandler::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testTransliterateWhenObjectHasNotMenuInterface()
    {
        $this->assertEquals(
            new InvalidArgumentException(sprintf(
                'Expected argument of type "%s", "%s" given.', MenuInterface::class, \stdClass::class
            )),
            $this->menuSlugHandler->transliterate('', '', new \stdClass())
        );
    }

    public function testTransliterateWhenMenuSlugHasEmptyEmpty()
    {
        $menu = $this->createMock(MenuInterface::class);

        $menu
            ->expects($this->once())
            ->method('getSlug')
            ->will($this->returnValue(''))
        ;

        $this->assertEquals(
            '',
            $this->menuSlugHandler->transliterate('', '', $menu)
        );
    }

    public function testTransliterateWhenIsEmptySlug()
    {
        $menu = $this->createMock(MenuInterface::class);

        $menu
            ->expects($this->once())
            ->method('getSlug')
            ->will($this->returnValue('slug'))
        ;

        $menu
            ->expects($this->once())
            ->method('isEmptySlug')
            ->will($this->returnValue(true))
        ;

        $this->assertNull(
            $this->menuSlugHandler->transliterate('', '', $menu)
        );
    }

    public function testTransliterate()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->assertEquals(
            'foo-slug',
            $this->menuSlugHandler->transliterate('foo-slug', '', $menu)
        );
    }
}
