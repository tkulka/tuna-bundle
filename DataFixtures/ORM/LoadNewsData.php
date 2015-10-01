<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use TheCodeine\NewsBundle\Entity\News;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $statics = array(
            'sarp' => array(
                'Organizacja', 'Informator', 'Kontakt', 'Partnerzy',
                'Organizacja', 'Informator', 'Kontakt', 'Partnerzy'
            ),
            'dofa' => array(
                'Festiwal', 'Kontakt', 'Partnerzy', 'Poprzednie edycje',
                'Festiwal', 'Kontakt', 'Partnerzy', 'Poprzednie edycje'
            ),
        );
        foreach ($statics as $branch => $newsNames) {
            foreach ($newsNames as $newsName) {
                $this->createPage($om, $newsName, $branch);
            }
        }

        $om->flush();
    }

    private function createPage($om, $name, $branch)
    {
        $news = new News();
        $news->setTitle($name);
        $news->setCategory($this->getReference($branch));
        $om->persist($news);
    }


    public function getOrder()
    {
        return 10;
    }

}