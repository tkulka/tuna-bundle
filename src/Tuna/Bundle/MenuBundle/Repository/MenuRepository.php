<?php

namespace TunaCMS\Bundle\MenuBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

class MenuRepository extends NestedTreeRepository implements MenuRepositoryInterface
{
    const OBJECT_KEY = '__object';
    const CHILDREN_KEY = '__children';

    public function getRoots()
    {
        return $this->findBy(['parent' => null]);
    }

    public function loadPublishedTree(MenuInterface $menu = null)
    {
        return $this->loadMenuTree($menu, true);
    }

    public function loadWholeTree(MenuInterface $menu = null)
    {
        return $this->loadMenuTree($menu, false);
    }

    protected function loadMenuTree(MenuInterface $node = null, $filterUnpublished = true)
    {
        $nodes = $this->getMenuTreeQuery($node, $filterUnpublished)->getResult();
        $tree = $this->buildTree($this->getArrifiedNodes($nodes));

        foreach ($tree as $menu) {
            $this->objectifyTree($menu);
        }

        return $node;
    }

    protected function objectifyTree(&$tree)
    {
        /* @var MenuInterface $object */
        $object = $tree[self::OBJECT_KEY];
        $object->setChildren(new ArrayCollection());

        foreach ($tree[self::CHILDREN_KEY] as $item) {
            if ($item[self::OBJECT_KEY]->getParent() !== $object) {
                // don't allow indirect children.
                // it can happen when you filter (e.g. unpublish) menu item - its children
                // would be still used to build a tree resulting in broken structure.
                continue;
            }

            $this->objectifyTree($item);
            $object->getChildren()->add($item[self::OBJECT_KEY]);
        }
    }

    protected function getArrifiedNodes(array &$array)
    {
        return array_map(function (MenuInterface $item) {
            return [
                'lft' => $item->getLft(),
                'rgt' => $item->getRgt(),
                'lvl' => $item->getLvl(),
                self::OBJECT_KEY => $item,
            ];
        }, $array);
    }

    protected function getMenuTreeQueryBuilder(MenuInterface $root = null, $filterUnpublished = true)
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.root, m.lft', 'ASC');

        if ($root) {
            $qb
                ->where($qb->expr()->lte('m.rgt', $root->getRgt()))
                ->andWhere($qb->expr()->gte('m.lft', $root->getLft()))
                ->andWhere('m.root = :root')
                ->setParameter('root', $root->getRoot());
        }

        if ($filterUnpublished) {
            $qb->andWhere('m.published = 1');
        }

        return $qb;
    }

    protected function getMenuTreeQuery(MenuInterface $root = null, $filterUnpublished = true)
    {
        return $this->getMenuTreeQueryBuilder($root, $filterUnpublished)->getQuery();
    }
}
