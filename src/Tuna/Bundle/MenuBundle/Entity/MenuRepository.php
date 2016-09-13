<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class MenuRepository extends NestedTreeRepository
{
    public function getNodesHierarchyQuery($node = null, $direct = false, array $options = array(), $includeNode = false)
    {
        return parent::getNodesHierarchyQuery($node, $direct, $options, $includeNode)->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
    }

    private function getFieldValue(Menu $object, $property)
    {
        return $this->getClassMetadata()->getReflectionProperty($property)->getValue($object);
    }

    private function nodeToArray(Menu $item)
    {
        return array(
            'lft' => $this->getFieldValue($item, 'lft'),
            'rgt' => $this->getFieldValue($item, 'rgt'),
            'lvl' => $this->getFieldValue($item, 'lvl'),
            '__object' => $item,
        );
    }

    public function getMenuTree($name = null)
    {
        $qb = $this->createQueryBuilder('m');

//        FIXME part1: don't know why this isn't working like this:
//        if ($name) {
//            $root = $this->findOneByLabel($name);
//            $qb->where($qb->expr()->lt('m.rgt', $this->getFieldValue($root, 'rgt')));
//            $qb->andWhere($qb->expr()->gt('m.lft', $this->getFieldValue($root, 'lft')));
//            $treeNodes[] = $this->nodeToArray($root);
//        }

        $nodes = $qb->getQuery()->getResult();
        array_walk($nodes, function (&$item) {
            $item = $this->nodeToArray($item);
        });

        $tree = $this->buildTree($nodes);

        if (!$name) {
            return $tree;
        }

        // FIXME part2: why this doesn't work with filtered nodes in buildTree($nodes)?
        foreach ($tree as $item) {
            if ($item['__object']->getLabel() == $name) {
                return $item['__children'];
            }
        }
    }

    public function getPageMap()
    {
        $results = $this->createQueryBuilder('m')
            ->where('m.page IS NOT NULL')
            ->getQuery()
            ->getResult();

        $items = array();
        foreach ($results as $result) {
            $items[$result->getPage()->getId()] = $result;
        }

        return $items;
    }
}
