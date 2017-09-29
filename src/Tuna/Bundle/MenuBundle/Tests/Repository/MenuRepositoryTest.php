<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Repository\MenuRepository;

class MenuRepositoryTest extends TestCase
{
    /**
     * @var MenuRepository
     */
    private $menuRepository;

    protected function setUp()
    {
        $this->menuRepository = $this->getMockBuilder(MenuRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findBy', 'loadMenuTree'])
            ->getMock()
        ;
    }

    public function testGetRoots()
    {
        $this->menuRepository
            ->expects($this->any())
            ->method('findBy')
            ->with($this->equalTo(['parent' => null]))
        ;

        $this->menuRepository->getRoots();
    }

    public function testLoadPublishedTreeWhenMenuIsEmpty()
    {
        $this->menuRepository
            ->expects($this->any())
            ->method('loadMenuTree')
            ->with(null, true)
        ;

        $this->menuRepository->loadPublishedTree();
    }

    public function testLoadPublishedTree()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuRepository
            ->expects($this->any())
            ->method('loadMenuTree')
            ->with($menu, true)
        ;

        $this->menuRepository->loadPublishedTree($menu);
    }

    public function testLoadWholeTreeWhenMenuIsEmpty()
    {
        $this->menuRepository
            ->expects($this->any())
            ->method('loadMenuTree')
            ->with(null, false)
        ;

        $this->menuRepository->loadWholeTree();
    }

    public function testLoadWholeTree()
    {
        $menu = $this->createMock(MenuInterface::class);

        $this->menuRepository
            ->expects($this->any())
            ->method('loadMenuTree')
            ->with($menu, false)
        ;

        $this->menuRepository->loadWholeTree($menu);
    }

}
