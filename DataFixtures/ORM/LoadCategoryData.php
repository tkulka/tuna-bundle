<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use TheCodeine\NewsBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    private $om;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $category = new Category();
        $category->setName('Default category');
        $category->setHasNews(true);

        $this->addReference('category', $category);

        $om->persist($category);
        $om->flush();
    }

    public function getOrder()
    {
        return 9;
    }
}