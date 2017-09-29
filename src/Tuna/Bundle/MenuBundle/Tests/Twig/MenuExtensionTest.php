<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Router;
use TunaCMS\Bundle\MenuBundle\Factory\MenuFactory;
use TunaCMS\Bundle\MenuBundle\Model\ExternalUrlInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuAliasInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Service\MenuManager;
use TunaCMS\Bundle\MenuBundle\Twig\MenuExtension;

class MenuExtensionTest extends TestCase
{
    const TEMPLATES = [
        'menu' => 'menu.html.twig',
        'menu_item' => 'menu_item.html.twig',
    ];

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

    /**
     * @var MenuFactory
     */
    private $menuFactory;

    protected function setUp()
    {
        $this->twig = $this->createMock('\Twig_Environment');
        $this->router = $this->createMock(Router::class);
        $this->menuManager = $this->createMock(MenuManager::class);
        $this->menuFactory = $this->createMock(MenuFactory::class);

        $this->extension = new MenuExtension(
            $this->twig,
            $this->menuManager,
            $this->menuFactory,
            $this->router,
            self::TEMPLATES
        );
    }

    public function testGetLinkWhenMenuIsExternalUrl()
    {
        $menu = $this->createMock(ExternalUrlInterface::class);
        $menu
            ->method('getUrl')
            ->will($this->returnValue('https://www.google.com/'))
        ;

        $this->assertEquals(
            'https://www.google.com/',
            $this->extension->getLink($menu)
        );
    }

    public function testGetLinkWhenMenuAliasHasEmptyTargetMenu()
    {
        $menu = $this->createMock(MenuAliasInterface::class);

        $menu
            ->method('getTargetMenu')
            ->will($this->returnValue(null))
        ;

        $this->assertNull($this->extension->getLink($menu));
    }

    public function testGetLinkWhenMenuAliasHasTargetMenu()
    {
        $menu = $this->createMock(MenuAliasInterface::class);
        $targetMenu = $this->createMock(MenuInterface::class);

        $menu
            ->method('getTargetMenu')
            ->will($this->returnValue($targetMenu))
        ;

        $targetMenu
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

    public function testGetLinkWhenMenuWithSlug()
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

    public function testRenderMenuWhenRoot()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuManager
            ->expects($this->once())
            ->method('getMenuTree')
            ->with($menu)
            ->will($this->returnValue($menu))
        ;

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(self::TEMPLATES['menu'], [
                'menu' => $menu,
                'name' => 'menu_name',
                'options' => [
                    'wrap' => true,
                    'root' => $menu,
                ],
                'templates' =>  self::TEMPLATES,
            ])
            ->will($this->returnValue($menu))
        ;

        $this->extension->renderMenu('menu_name', ['root' => $menu]);
    }

    public function testRenderMenuWithCustomTemplate()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuManager
            ->expects($this->once())
            ->method('getMenuTree')
            ->with($menu)
            ->will($this->returnValue($menu))
        ;

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('custom_menu.html.twig', [
                'menu' => $menu,
                'name' => 'menu_name',
                'templates' => [
                    'menu' => 'custom_menu.html.twig',
                    'menu_item' => self::TEMPLATES['menu_item'],
                ],
                'options' => [
                    'wrap' => true,
                    'root' => $menu,
                ],
            ])
            ->will($this->returnValue($menu))
        ;

        $this->extension->renderMenu(
            'menu_name',
            [ 'root' => $menu ],
            [ 'menu' => 'custom_menu.html.twig' ]
        );
    }

    public function testRenderMenuWhenFromNameIsEmpty()
    {
        $this->menuManager
            ->expects($this->once())
            ->method('findMenuItemByName')
            ->with('menu_name')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals('', $this->extension->renderMenu('menu_name'));
    }

    public function testRenderMenuWhenFromName()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuManager
            ->expects($this->once())
            ->method('findMenuItemByName')
            ->with('menu_name')
            ->will($this->returnValue($menu))
        ;

        $this->menuManager
            ->expects($this->once())
            ->method('getMenuTree')
            ->with($menu)
            ->will($this->returnValue($menu))
        ;

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(self::TEMPLATES['menu'], [
                'menu' => $menu,
                'name' => 'menu_name',
                'templates' =>  self::TEMPLATES,
                'options' => [
                    'wrap' => true,
                    'root' => $menu,
                ],
            ])
            ->will($this->returnValue($menu))
        ;

        $this->extension->renderMenu('menu_name');
    }

    public function testResolveMenuType()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuFactory
            ->expects($this->once())
            ->method('getTypeName')
            ->with($menu)
            ->will($this->returnValue('type_name'))
        ;

        $this->assertEquals(
            'type_name',
            $this->extension->resolveMenuType($menu)
        );
    }
}
