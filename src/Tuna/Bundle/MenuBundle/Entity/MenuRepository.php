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

    public function getPageMap()
    {
        $results = $this->createQueryBuilder('m')
            ->where('m.page IS NOT NULL')
            ->getQuery()
            ->getResult();

        $items = array();
        foreach ($results as $result) {
            if ($result->getPage()) {
                $items[$result->getPage()->getId()] = $result;
            }
        }

        return $items;
    }
}
