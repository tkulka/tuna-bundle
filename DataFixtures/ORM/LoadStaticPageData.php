<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use TheCodeine\PageBundle\Entity\Page;

class LoadStaticPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $statics = array(
            'sarp' => array('Organizacja', 'Informator', 'Kontakt', 'Partnerzy'),
            'dofa' => array('Festiwal', 'Kontakt', 'Partnerzy', 'Poprzednie edycje'),
        );
        foreach ($statics as $branch => $pageNames) {
            foreach ($pageNames as $pageName) {
                $this->createPage($om, $pageName, $branch);
            }
        }

        $om->flush();
    }

    private function createPage($om, $name, $branch)
    {
        $page = new Page();
        $page->setTitle($name);
        $page->setCategory($this->getReference($branch));
        $om->persist($page);
    }


    public function getOrder()
    {
        return 10;
    }

}