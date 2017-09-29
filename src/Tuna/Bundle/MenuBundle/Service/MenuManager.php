<?php

namespace TunaCMS\Bundle\MenuBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\MenuBundle\Repository\MenuRepositoryInterface;

class MenuManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var MenuRepositoryInterface
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct($class, EntityManagerInterface $entityManager)
    {
        $this->class = $class;
        $this->entityManager = $entityManager;

        $this->repository = $this->entityManager->getRepository($this->class);
    }

    /**
     * @return MenuInterface
     */
    public function getMenuInstance()
    {
        return new $this->class();
    }

    public function getClassName()
    {
        return $this->class;
    }

    /**
     * @param int $id
     *
     * @return null|object
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $order
     */
    public function saveOrder(array $order)
    {
        if (!$order) {
            return;
        }

        $nodes = [];
        $entities = $this->repository->findAll();

        if (!$entities) {
            return;
        }

        /* @var MenuInterface $entity */
        foreach ($entities as $entity) {
            $nodes[$entity->getId()] = $entity;
        }

        foreach ($order as $nodeTreeData) {
            $nodes[(int)$nodeTreeData['id']]->setTreeData(
                (int)$nodeTreeData['left'],
                (int)$nodeTreeData['right'],
                (int)$nodeTreeData['depth'],
                isset($nodes[$nodeTreeData['parent_id']]) ? $nodes[$nodeTreeData['parent_id']] : null
            );
        }

        $this->entityManager->flush();
    }

    /**
     * @param MenuInterface $root
     *
     * @return MenuInterface
     */
    public function getMenuTree(MenuInterface $root)
    {
        return $this->repository->loadPublishedTree($root);
    }

    /**
     * @return MenuRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $label
     *
     * @return MenuInterface|null
     */
    public function findMenuItemByLabel($label)
    {
        return $this->repository->findOneBy(['label' => $label]);
    }

    /**
     * @param string $name
     *
     * @return MenuInterface|null
     */
    public function findMenuItemByName($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->class;
    }
}
