<?php

namespace TheCodeine\MenuBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\RequestStack;
use TheCodeine\MenuBundle\Entity\MenuInterface;
use TheCodeine\MenuBundle\Entity\MenuRepository;
use TunaCMS\PageComponent\Model\PageInterface;

class MenuManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var string
     */
    protected $pageModel;

    /**
     * @var MenuRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected $defaultLocale;

    protected $locale;

    public function __construct($class, $formType, $pageModel, EntityManager $entityManager, RequestStack $requestStack)
    {
        $this->class = $class;
        $this->pageModel = $pageModel;
        $this->formType = $formType;
        $this->entityManager = $entityManager;

        $this->repository = $this->entityManager->getRepository($this->class);


        if (($request = $requestStack->getCurrentRequest())) {
            $this->defaultLocale = $request->getDefaultLocale();
            $this->locale = $request->getLocale();
        }
    }

    /**
     * @return MenuInterface
     */
    public function getMenuInstance()
    {
        return new $this->class();
    }

    public function getFormType()
    {
        return $this->formType;
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
     * @return mixed
     */
    public function getPageMap()
    {
        return $this->repository->getPageMap();
    }

    /**
     * @param array $order
     */
    public function saveOrder(array $order)
    {
        $nodes = [];
        $entities = $this->repository->findAll();

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
     * @param bool $filterUnpublished
     * @param string $locale
     * @param string $defaultLocale
     *
     * @return array
     */
    public function getMenuTree(MenuInterface $root = null, $filterUnpublished = true, $locale = null, $defaultLocale = null)
    {
        $locale = $locale ?: $this->locale;
        $defaultLocale = $defaultLocale ?: $this->defaultLocale;

        return $this->repository->getMenuTree($root, $filterUnpublished, $locale, $defaultLocale);
    }

    /**
     * @return MenuRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return Query
     */
    public function getStandalonePagesPaginationQuery()
    {
        return $this->repository->getStandalonePagesPaginationQuery($this->pageModel, $this->class);
    }

    public function isTranslated(MenuInterface $menu, $locale = null)
    {
        if (!$locale) {
            $locale = $this->locale;
        }

        if ($locale == $this->defaultLocale) {
            return true;
        }

        foreach ($menu->getTranslations() as $translation) {
            if ($translation->getField() == 'label' && $translation->getLocale() == $locale) {
                return true;
            }
        }

        return false;
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
     * @param $page PageInterface
     *
     * @return MenuInterface|null
     */
    public function findMenuItemByPage(PageInterface $page)
    {
        return $this->repository->findOneByPage($page);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->class;
    }
}
