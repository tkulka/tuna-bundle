<?php

namespace TheCodeine\MenuBundle\Entity;

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

    public function getMenuTree($name = null, $filterUnpublished = true)
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.root, m.lft', 'ASC');

        if ($name) {
            $root = $this->findOneByLabel($name);
            if (!$root) {
                throw new \Exception('There\'s no menu like this');
            }
            $qb->where($qb->expr()->lte('m.rgt', $this->getFieldValue($root, 'rgt')));
            $qb->andWhere($qb->expr()->gte('m.lft', $this->getFieldValue($root, 'lft')));
        }

        $nodes = $qb->getQuery()->getResult();

        if (!$filterUnpublished) {
            array_walk($nodes, function (&$item) {
                $item = $this->nodeToArray($item);
            });
        } else {
            $filteredNodes = array();
            $unpublishedRgt = 0;

            foreach ($nodes as $node) {

                $nodeRgt = $this->getFieldValue($node, 'rgt');
                if (!$node->isPublished()) {
                    $unpublishedRgt = $nodeRgt;
                }

                if ($unpublishedRgt < $nodeRgt) {
                    $filteredNodes[] = $this->nodeToArray($node);
                }
            }

            $nodes = $filteredNodes;
        }

        $tree = $this->buildTree($nodes);

        return $name ? $tree[0]['__children'] : $tree;
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
