<?php

namespace Repository\TheCodeine\PageBundle\Repository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use TheCodeine\PageBundle\Entity\Page;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class PageRepositorySpec extends ObjectBehavior
{
    function let(EntityManager $entityManager, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($entityManager, $classMetadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('TheCodeine\PageBundle\Repository\PageRepository');
    }
}
