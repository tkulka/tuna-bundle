<?php

namespace TunaCMS\Bundle\MenuBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct($class, EntityManager $entityManager)
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
        $nodes = [];
        $entities = $this->repository->findAll();

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
     * @param $label
     *
     * @return MenuInterface|null
     */
    public function findMenuItemByLabel($label)
    {
        return $this->repository->findOneByLabel($label);
    }

    /**
     * @param $label
     *
     * @return MenuInterface|null
     */
    public function findMenuItemByName($label)
    {
        return $this->repository->findOneByName($label);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->class;
    }
}
