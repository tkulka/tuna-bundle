<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\ORM\Query;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class MenuRepository extends NestedTreeRepository
{
    /**
     * @param null $node
     * @param bool $direct
     * @param array $options
     * @param bool $includeNode
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function getNodesHierarchyQuery($node = null, $direct = false, array $options = [], $includeNode = false)
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
        return [
            'lft' => $this->getFieldValue($item, 'lft'),
            'rgt' => $this->getFieldValue($item, 'rgt'),
            'lvl' => $this->getFieldValue($item, 'lvl'),
            '__object' => $item,
        ];
    }

    /**
     * @param null $root Menu root item
     * @param bool $filterUnpublished
     * @return array
     * @throws \Exception
     */
    public function getMenuTree(Menu $root = null, $filterUnpublished = true)
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.root, m.lft', 'ASC');

        if ($root) {
            if (!$root) {
                throw new \Exception('There\'s no menu like this.');
            }
            $qb
                ->where($qb->expr()->lte('m.rgt', $this->getFieldValue($root, 'rgt')))
                ->andWhere($qb->expr()->gte('m.lft', $this->getFieldValue($root, 'lft')))
                ->andWhere('m.root = :root')
                ->setParameter('root', $root->getRoot());
        }

        $nodes = $qb->getQuery()->getResult();

        if (!$filterUnpublished) {
            array_walk($nodes, function (&$item) {
                $item = $this->nodeToArray($item);
            });
        } else {
            $filteredNodes = [];
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

        if ($root && !array_key_exists(0, $tree)) {
            throw new \Exception('Something wrong happened during building this menu.');
        }

        return $root ? $tree[0]['__children'] : $tree;
    }

    /**
     * @return array
     */
    public function getPageMap()
    {
        $results = $this->createQueryBuilder('m')
            ->where('m.page IS NOT NULL')
            ->getQuery()
            ->getResult();

        $items = [];
        foreach ($results as $result) {
            $items[$result->getPage()->getId()] = $result;
        }

        return $items;
    }

    /**
     * @param string $class
     *
     * @return Query
     */
    public function getStandalonePagesPaginationQuery($class)
    {
        return $this->_em->createQuery("
            SELECT p FROM ${class} p WHERE p.id NOT IN (
                SELECT p2.id FROM TheCodeineMenuBundle:Menu m JOIN ${class} p2 WITH p2 = m.page
            )
        ");
    }
}
