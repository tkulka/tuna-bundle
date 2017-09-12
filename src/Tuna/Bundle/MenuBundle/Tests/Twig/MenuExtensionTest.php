<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Router;
use TunaCMS\Bundle\FileBundle\Tests\Fixtures\MenuInterface; // todo remove when MenuInterface will support getSlug method
use TunaCMS\Bundle\MenuBundle\Service\MenuManager;
use TunaCMS\Bundle\MenuBundle\Twig\MenuExtension;

class MenuExtensionTest extends TestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var MenuManager
     */
    private $menuManager;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var MenuExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->twig = $this->createMock('\Twig_Environment');
        $this->router = $this->createMock(Router::class);
        $this->menuManager = $this->createMock(MenuManager::class);

        $this->extension = new MenuExtension(
            $this->twig,
            $this->menuManager,
            $this->router,
            'default_template.twig'
        );
    }

    public function testGetLinkWhenMenuWithUrl()
    {
        $menu = $this->createMock(MenuInterface::class);
        $menu
            ->method('getUrl')
            ->will($this->returnValue('https://www.google.com/'))
        ;

        $this->assertEquals(
            'https://www.google.com/',
            $this->extension->getLink($menu)
        );
    }

    public function testGetLinkWhenMenuWithoutUrl()
    {
        $menu = $this->createMock(MenuInterface::class);

        $menu
            ->method('getSlug')
            ->will($this->returnValue('foo'))
        ;

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with('tuna_menu_item', ['slug' => 'foo'])
            ->will($this->returnValue('/root/foo'))
        ;

        $this->assertEquals(
            '/root/foo',
            $this->extension->getLink($menu)
        );
    }
}
