<?php

namespace TunaCMS\Bundle\NodeBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository as BaseNestedTreeRepository;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeRepository extends BaseNestedTreeRepository
{
    const OBJECT_KEY = '__object';
    const CHILDREN_KEY = '__children';

    public function getMenuRoots()
    {
        return $this->findByParent(null);
    }

    public function loadNodeTree(NodeInterface $node = null)
    {
        $nodes = $this->getMenuTreeQuery($node)->getResult();
        $tree = $this->buildTree($this->getArrifiedNodes($nodes));

        foreach ($tree as $menu) {
            $this->objectifyTree($menu);
        }

        if (!$node) {
            // if no root was given return array of menu roots
            return array_map(function ($item) {
                return $item[self::OBJECT_KEY];
            }, $tree);
        }

        return $node;
    }

    protected function objectifyTree(&$tree)
    {
        /* @var NodeInterface $object */
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
        return array_map(function ($item) {
            return [
                'lft' => $item->getLft(),
                'rgt' => $item->getRgt(),
                'lvl' => $item->getLvl(),
                self::OBJECT_KEY => $item,
            ];
        }, $array);
    }

    protected function getMenuTreeQueryBuilder(NodeInterface $root = null, $filterUnpublished = true, $locale = null, $defaultLocale = null)
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

        if ($locale && $locale !== $defaultLocale) {
            $qb
                ->leftJoin('m.translations', 't', Expr\Join::WITH, "t.field = 'label' AND t.locale = :locale")
                ->andWhere('m.lvl = 0 OR m.lvl > 0 AND t IS NOT NULL')// menu root don't have to be translated.
                ->setParameter('locale', $locale);
        }

        return $qb;
    }

    protected function getMenuTreeQuery(NodeInterface $root = null, $filterUnpublished = true, $locale = null, $defaultLocale = null)
    {
        return $this->getMenuTreeQueryBuilder($root, $filterUnpublished, $locale, $defaultLocale)->getQuery();
    }
}
