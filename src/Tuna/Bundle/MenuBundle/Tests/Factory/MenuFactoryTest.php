<?php

namespace TunaCMS\Bundle\MenuBundle\Tests\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TypeConfig;
use TunaCMS\Bundle\MenuBundle\Factory\MenuFactory;
use TunaCMS\Bundle\MenuBundle\Tests\Model\DummyMenu;
use TunaCMS\Bundle\MenuBundle\Tests\Model\DummyNode;
use TunaCMS\Bundle\MenuBundle\Tests\Model\FooNode;

class MenuFactoryTest extends TestCase
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * @var MenuFactory
     */
    private $menuFactory;

    protected function setUp()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->menuFactory = $this->getMockBuilder(MenuFactory::class)
            ->setMethods(['getTypeConfig', 'getTypesClassMap'])
            ->getMock()
        ;

        $typeConfig = new TypeConfig();
        $typeConfig
            ->setName(MenuFactory::BASE_TYPE)
            ->setModel(DummyMenu::class)
            ->setForm('App\Form\MenuType')
            ->setTemplates([
                'edit' => 'menu.html.twig'
            ])
        ;

        $this->menuFactory
            ->expects($this->any())
            ->method('getTypeConfig')
            ->will($this->returnValueMap([
                [ MenuFactory::BASE_TYPE, $typeConfig ],
                [ null, $typeConfig ],
            ]))
        ;

        $this->menuFactory
            ->expects($this->any())
            ->method('getTypesClassMap')
            ->will($this->returnValue([
                DummyMenu::class => MenuFactory::BASE_TYPE,
            ]))
        ;
    }

    public function testGetTypesWhenCollectionIsEmpty()
    {
        $menuFactory = new MenuFactory();
        $this->assertCount(0, $menuFactory->getTypes());
        $this->assertInstanceOf(ArrayCollection::class, $menuFactory->getTypes());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type "type_not_exists" is not registered.
     */
    public function testGetInstanceWhenTypeNotExists()
    {
        $menuFactory = new MenuFactory();
        $menuFactory->getInstance('type_not_exists');
    }

    public function testGetInstance()
    {
        $this->assertInstanceOf(
            DummyMenu::class,
            $this->menuFactory->getInstance()
        );

        $this->assertInstanceOf(
            DummyMenu::class,
            $this->menuFactory->getInstance(MenuFactory::BASE_TYPE)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type "type_not_exists" is not registered.
     */
    public function testGetModelWhenTypeNotExists()
    {
        $menuFactory = new MenuFactory();
        $menuFactory->getModel('type_not_exists');
    }

    public function testGetModel()
    {
        $this->assertEquals(
            DummyMenu::class,
            $this->menuFactory->getModel()
        );

        $this->assertEquals(
            DummyMenu::class,
            $this->menuFactory->getModel(MenuFactory::BASE_TYPE)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type "type_not_exists" is not registered.
     */
    public function testGetFormClassWhenTypeNotExists()
    {
        $menuFactory = new MenuFactory();
        $menuFactory->getFormClass('type_not_exists');
    }

    public function testGetFormClass()
    {
        $this->assertEquals(
            'App\Form\MenuType',
            $this->menuFactory->getFormClass()
        );

        $this->assertEquals(
            'App\Form\MenuType',
            $this->menuFactory->getFormClass(MenuFactory::BASE_TYPE)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type "type_not_exists" is not registered.
     */
    public function testGetTemplateWhenTypeNotExists()
    {
        $menuFactory = new MenuFactory();
        $menuFactory->getFormClass('type_not_exists');
    }

    public function testGetTemplateClass()
    {
        $this->assertEquals(
            'menu.html.twig',
            $this->menuFactory->getTemplate(null, 'edit')
        );

        $this->assertEquals(
            'menu.html.twig',
            $this->menuFactory->getTemplate(MenuFactory::BASE_TYPE, 'edit')
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type "TunaCMS\Bundle\MenuBundle\Tests\Model\FooNode" is not registered.
     */
    public function testGetTypeNameWhenTypeNotExists()
    {
        $menuFactory = new MenuFactory();
        $menuFactory->getTypeName(new FooNode());
    }

    public function testGetTypeName()
    {
        $this->assertEquals(
            MenuFactory::BASE_TYPE,
            $this->menuFactory->getTypeName(new DummyMenu())
        );
    }

    /**
     * @dataProvider getRegisterType
     *
     * @param array $configs
     * @param array $expectedTypes
     */
    public function testRegisterType(array $configs, array $expectedTypes)
    {
        $menuFactory = new MenuFactory();
        /** @var TypeConfig[] $types */
        $types = [];

        foreach ($expectedTypes as $expectedType) {
            $typeConfig = new TypeConfig();

            foreach ($expectedType as $propertyPath => $value) {
                $this->accessor->setValue($typeConfig, $propertyPath, $value);
            }

            $types[$typeConfig->getName()] = $typeConfig;
        }

        foreach ($configs as $config) {
            $menuFactory->registerType($config['name'], $config);
        }

        $this->assertEquals(
            new ArrayCollection($types),
            $menuFactory->getTypes()
        );

        foreach ($types as $name => $object) {
            $class = $object->getModel();

            $this->assertEquals(
                $name,
                $menuFactory->getTypeName(new $class())
            );
        }
    }

    /**
     * @return array
     */
    public function getRegisterType()
    {
        return [
            [
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'App\Form\NodeType',
                        'templates' => [
                            'edit' => 'node.html.twig'
                        ],
                    ],
                ],
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'App\Form\NodeType',
                        'templates' => [
                            'edit' => 'node.html.twig'
                        ],
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'App\Form\NodeType',
                        'templates' => [
                            'edit' => 'node.html.twig'
                        ],
                    ],
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'Dummy\Form\DummyNodeType',
                        'templates' => [
                            'edit' => 'dummy_node.html.twig'
                        ],
                    ],
                ],
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'Dummy\Form\DummyNodeType',
                        'templates' => [
                            'edit' => 'dummy_node.html.twig'
                        ],
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => 'App\Form\NodeType',
                        'templates' => [
                            'edit' => 'node.html.twig'
                        ],
                    ],
                    [
                        'name' => 'node',
                        'model' => FooNode::class,
                        'form' => 'Foo\Form\DummyNodeType',
                        'templates' => [
                            'edit' => 'foo_node.html.twig'
                        ],
                    ],
                ],
                [
                    [
                        'name' => 'node',
                        'model' => FooNode::class,
                        'form' => 'Foo\Form\DummyNodeType',
                        'templates' => [
                            'edit' => 'foo_node.html.twig'
                        ],
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'form' => '',
                        'templates' => [],
                    ],
                    [
                        'name' => 'menu',
                        'model' => DummyMenu::class,
                        'form' => '',
                        'templates' => [],
                    ],
                ],
                [
                    [
                        'name' => 'node',
                        'model' => DummyNode::class,
                        'templates' => [],
                    ],
                    [
                        'name' => 'menu',
                        'model' => DummyMenu::class,
                        'templates' => [],
                    ],
                ],
            ],
        ];
    }
}
