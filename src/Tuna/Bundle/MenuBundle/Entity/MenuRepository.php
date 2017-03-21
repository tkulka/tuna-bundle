<?php

namespace TheCodeine\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param Menu|null $root
     * @param bool $filterUnpublished
     * @param $locale
     * @param $defaultLocale
     * @return array|Menu
     */
    public function getMenuTree(Menu $root = null, $filterUnpublished = true, $locale, $defaultLocale)
    {
        $nodes = $this->getMenuTreeQuery($root, $filterUnpublished, $locale, $defaultLocale)->getResult();
        $tree = $this->buildTree($this->getArrifiedNodes($nodes));

        foreach ($tree as $menu) {
            $this->objectifyTree($menu);
        }

        if (!$root) {
            // if no root was given return array of menu roots
            return array_map(function ($item) {
                return $item['__object'];
            }, $tree);
        }

        return $root;
    }

    /**
     * @return array|Menu[]
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

    protected function objectifyTree(&$tree)
    {
        /* @var $menu Menu */
        $menu = $tree['__object'];
        $menu->setChildren(new ArrayCollection());

        foreach ($tree['__children'] as $item) {
            if ($item['__object']->getParent() !== $menu) {
                // don't allow indirect children.
                // it can happen when you filter (e.g. unpublish) menu item - its children
                // would be still used to build a tree resulting in broken structure.
                continue;
            }

            $this->objectifyTree($item);
            $menu->getChildren()->add($item['__object']);
        }
    }

    protected function getFieldValue(Menu $object, $property)
    {
        return $this->getClassMetadata()->getReflectionProperty($property)->getValue($object);
    }

    protected function getArrifiedNodes(array &$array)
    {
        return array_map(function ($item) {
            return [
                'lft' => $item->getLft(),
                'rgt' => $item->getRgt(),
                'lvl' => $item->getLvl(),
                '__object' => $item,
            ];
        }, $array);
    }

    protected function getMenuTreeQueryBuilder(Menu $root = null, $filterUnpublished = true, $locale = null, $defaultLocale = null)
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
            $qb
                ->andWhere('m.publishDate <= :now')
                ->setParameter('now', new \DateTime());
        }

        if ($locale && $locale !== $defaultLocale) {
            $qb
                ->leftJoin('m.translations', 't', Query\Expr\Join::WITH, "t.field = 'label' AND t.locale = :locale")
                ->andWhere('m.lvl = 0 OR m.lvl > 0 AND t IS NOT NULL')// menu root don't have to be translated.
                ->setParameter('locale', $locale);
        }

        return $qb;
    }

    protected function getMenuTreeQuery(Menu $root = null, $filterUnpublished = true, $locale = null, $defaultLocale = null)
    {
        return $this->getMenuTreeQueryBuilder($root, $filterUnpublished, $locale, $defaultLocale)->getQuery();
    }
}
