<?php

namespace TheCodeine\TagBundle\Doctrine;

use TheCodeine\TagBundle\Entity\Tag;
use TheCodeine\TagBundle\Model\TagManagerInterface;

use Doctrine\Common\Persistence\ObjectManager;

class TagManager implements TagManagerInterface
{
    /**
     * Tag entity repository class
     *
     * @var Doctrine\ORM\EntityRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om)
    {
        $this->repository = $om->getRepository('TheCodeine\TagBundle\Entity\Tag');
    }

    public function createTag()
    {
        return new Tag();
    }

    public function findTagsByName(array $tagNames)
    {
        $qb = $this->repository->createQueryBuilder('t');

        return $qb->where($qb->expr()->in('t.name', $tagNames))->getQuery()->getResult();
    }
}