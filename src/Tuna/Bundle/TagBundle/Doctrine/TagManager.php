<?php

namespace TunaCMS\Bundle\TagBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use TunaCMS\Bundle\TagBundle\Entity\Tag;

class TagManager implements TagManagerInterface
{
    /**
     * Tag entity repository class
     *
     * @var EntityRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om)
    {
        $this->repository = $om->getRepository(Tag::class);
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
