<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;

class NewsRepository extends EntityRepository
{
    public function findAllPublished()
    {
        return $this->findBy(['published' => true]);
    }

    public function getListQuery($type = null)
    {
        $type = ucfirst(strtolower($type));
        $qb = $this->createQueryBuilder('n');
        if ($type) {
            $qb->where('n INSTANCE OF TheCodeineNewsBundle:' . $type);
        }

        return $qb->getQuery();
    }

    public function getSingleItem($slug)
    {
        $qb = $this->createQueryBuilder('n')
            ->andWhere('n.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1);

        return $this->addTranslationWalker($qb)->getResult();
    }

    protected function addTranslationWalker(QueryBuilder $qb)
    {
        return $qb
            ->getQuery()
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(TranslatableListener::HINT_INNER_JOIN, true);
    }

    public function getItemsForTag($tag)
    {
        if (!$tag) {
            return false;
        }

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 'tag')
            ->where('p.published=1')
            ->orderBy('p.createdAt', 'DESC');

        if (is_string($tag)) {
            $qb->andWhere('tag.name = :tag')
                ->setParameter('tag', $tag);
        } else {
            $qb->andWhere('tag = :tag')
                ->setParameter('tag', $tag);
        }

        return $this->addTranslationWalker($qb);
    }

    public function getSimilar($limit = 2, AbstractNews $news)
    {
        $tagNames = [];
        foreach ($news->getTags() as $tag) {
            $tagNames[] = $tag->getName();
        }

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 'tag')
            ->where('p.published=1')
            ->andWhere('p.id != :article_id')
            ->setParameter('article_id', $news->getId())
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit);

        if (count($tagNames) > 0) {
            $qb->andWhere($qb->expr()->in('tag.name', $tagNames));
        }

        return $this->addTranslationWalker($qb)->getResult();
    }

    public function getLatestItems($limit = 3)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.published=1')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit);

        return $this->addTranslationWalker($qb)->getResult();
    }
}