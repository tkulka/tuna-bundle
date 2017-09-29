<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Repository\MenuRepositoryInterface;
use TunaCMS\Bundle\MenuBundle\Tests\Model\DummyMenu;
use TunaCMS\Bundle\MenuBundle\Service\MenuManager;

class MenuManagerTest extends TestCase
{
    const MENU_CLASS = DummyMenu::class;

    /**
     * @var MenuRepositoryInterface
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuManager
     */
    private $menuManager;

    protected function setUp()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(MenuRepositoryInterface::class);

        $this->em
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(self::MENU_CLASS))
            ->will($this->returnValue($this->repository))
        ;

        $this->menuManager = new MenuManager(DummyMenu::class, $this->em);
    }

    public function testGetMenuInstance()
    {
        $this->assertInstanceOf(
            self::MENU_CLASS,
            $this->menuManager->getMenuInstance()
        );
    }

    public function testGetClassName()
    {
        $this->assertEquals(
            self::MENU_CLASS,
            $this->menuManager->getClassName()
        );
    }

    public function testFindMenuById()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(234))
            ->will($this->returnValue(null))
        ;

        $this->menuManager->find(234);
    }

    public function testSaveOrderWhenOrderIsEmpty()
    {
        $this->repository
            ->expects($this->never())
            ->method('findAll')
        ;

        $this->em
            ->expects($this->never())
            ->method('flush')
        ;

        $this->menuManager->saveOrder([]);
    }

    public function testSaveOrderWhenRepositoryIsEmpty()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]))
        ;

        $this->em
            ->expects($this->never())
            ->method('flush')
        ;

        $this->menuManager->saveOrder([
            [
                'id' => 1,
                'left' => 1,
                'right' => 10,
                'depth' => 0,
            ]
        ]);
    }

    public function testSaveOrder()
    {
        $menu1 = $this->createMenuMock(1, [ 1, 10, 0, null ]);
        $menu3 = $this->createMenuMock(3, [ 2, 7, 1, $menu1 ]);
        $menu2 = $this->createMenuMock(2, [ 5, 6, 2, $menu3 ]);
        $menu4 = $this->createMenuMock(4, [ 8, 9, 1, $menu1 ]);
        $menu5 = $this->createMenuMock(5, [ 3, 4, 2, $menu3 ]);

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([
                $menu1,
                $menu2,
                $menu3,
                $menu4,
                $menu5,
            ]))
        ;

        $this->em
            ->expects($this->once())
            ->method('flush')
        ;

        $this->menuManager->saveOrder([
            [
                'id' => 1,
                'left' => 1,
                'right' => 10,
                'depth' => 0,
                'parent_id' => null
            ],
            [
                'id' => 3,
                'left' => 2,
                'right' => 7,
                'depth' => 1,
                'parent_id' => 1
            ],
            [
                'id' => 5,
                'left' => 3,
                'right' => 4,
                'depth' => 2,
                'parent_id' => 3
            ],
            [
                'id' => 2,
                'left' => 5,
                'right' => 6,
                'depth' => 2,
                'parent_id' => 3
            ],
            [
                'id' => 4,
                'left' => 8,
                'right' => 9,
                'depth' => 1,
                'parent_id' => 1
            ]
        ]);
    }

    public function testGetMenuTree()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->repository
            ->expects($this->once())
            ->method('loadPublishedTree')
            ->with($this->equalTo($menu))
            ->will($this->returnValue(null))
        ;

        $this->menuManager->getMenuTree($menu);
    }

    public function testGetRepository()
    {
        $this->assertEquals(
            $this->repository,
            $this->menuManager->getRepository()
        );
    }

    public function testFindMenuItemByName()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['name' => 'name']))
            ->will($this->returnValue(null))
        ;

        $this->menuManager->findMenuItemByName('name');
    }

    public function testToString()
    {
        $this->assertEquals(
            self::MENU_CLASS,
            (string) $this->menuManager
        );
    }

    /**
     * @param integer $id
     * @param array $treeData
     *
     * @return MenuInterface
     */
    private function createMenuMock($id, array $treeData)
    {
        $menu = $this->createMock(MenuInterface::class);

        $menu
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($id))
        ;

        $menu
            ->expects($this->once())
            ->method('setTreeData')
            ->with(...$treeData)
        ;

        return $menu;
    }
}
