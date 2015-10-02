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

        $gallery = new Category();
        $gallery->setName('Gallery');
        $gallery->setHasNews(true);

        $attachment = new Category();
        $attachment->setName('Attachment');
        $attachment->setHasNews(true);

        $this->addReference('category', $category);
        $this->addReference('gallery', $gallery);
        $this->addReference('attachment', $attachment);

        $om->persist($category);
        $om->persist($gallery);
        $om->persist($attachment);
        $om->flush();
    }

    public function getOrder()
    {
        return 9;
    }
}