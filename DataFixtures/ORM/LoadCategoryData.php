<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Entity\NewsCategory;
use TheCodeine\PageBundle\Entity\Page;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    private $om;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $this->om = $om;

        $sarp = new Category();
        $sarp->setName('Sarp');
        $sarp->setHasNews(true);

        $dofa = new Category();
        $dofa->setName('Dofa');


        $this->addReference('sarp', $sarp);
        $this->addReference('dofa', $dofa);


        $om->persist($sarp);
        $om->persist($dofa);
        $om->flush();

        $sarpCategories = array(
            'Aktualności' => false,
            'Organizacja' => false,
            'Informator' => true,
            'Kontakt' => true,
            'Partnerzy' => true,
        );

        foreach ($sarpCategories as $name => $isSinglePage) {
            $category = new Category();
            $category
                ->setName($name)
                ->setParent($sarp)
                ->setSinglePage($isSinglePage);
            if ($name == 'Aktualności') {
                $category->setHasNews(true);
                $newsCategories = array('Wydarzenia', 'DEBATA', 'Konkursy', 'Konferencje');
                foreach ($newsCategories as $ncName) {
                    $newsCategory = new NewsCategory();
                    $newsCategory
                        ->setName($ncName)
                        ->setCategory($category);
                    $om->persist($newsCategory);
                }
            }
            $om->persist($category);

            if ($isSinglePage) {
                $page = new Page();
                $page
                    ->setTitle($name)
                    ->setCategory($category)
                    ->setPublished(true);
                $om->persist($page);
            } elseif ($name == 'Organizacja') {
                $subcategories = array(
                    'Historia',
                    'Ludzie',
                    'Indeks architektów',
                    'Członkostwo',
                    'Cele',
                    'Status',
                );
                foreach ($subcategories as $subcategoryName) {
                    $page = new Page();
                    $page
                        ->setTitle($subcategoryName)
                        ->setCategory($category)
                        ->setPublished(true);
                    $om->persist($page);
                }
            }
        }

        // top2 subcategories
        $festiwale = new Category();
        $festiwale->setName('Festiwale');
        $festiwale->setParent($dofa);
        $festiwale->setIsGroup(true);

        $om->persist($festiwale);


        $this->addDofa('\'14', $festiwale);
        $this->addDofa('\'13', $festiwale);
        $this->addDofa('\'12', $festiwale);
        $this->addDofa('\'11', $festiwale);

        $om->flush();


    }

    private function addDofa($number, $parent)
    {
        $c11 = new Category();
        $c11->setName($number);
        $c11->setParent($parent);
        $c11->setIsGroup(true);


        $c111_ = new Category();
        $c111_->setName('Aktualności');
        $c111_->setParent($c11);
        $c111_->setHasNews(true);

        $c111 = new Category();
        $c111->setName('Wystawy konkursowe');
        $c111->setParent($c11);
        $c111->setIsGroup(true);

        $c112 = new Category();
        $c112->setName('Wystawy problemowe');
        $c112->setParent($c11);
        $c112->setIsGroup(true);

        $c113 = new Category();
        $c113->setName('Otwarte miasto');
        $c113->setParent($c11);
        $c113->setIsGroup(true);

        $c114 = new Category();
        $c114->setName('Projekcje');
        $c114->setParent($c11);
        $c114->setIsGroup(true);

        $c115 = new Category();
        $c115->setName('Konfrontacje');
        $c115->setParent($c11);
        $c115->setIsGroup(true);

        $c116 = new Category();
        $c116->setName('Przestrzeń');
        $c116->setParent($c11);
        $c116->setIsGroup(true);

        $c117 = new Category();
        $c117->setName('Publikacje');
        $c117->setParent($c11);
        $c117->setIsGroup(true);

        $this->om->persist($c11);
        $this->om->persist($c111_);
        $this->om->persist($c111);
        $this->om->persist($c112);
        $this->om->persist($c113);
        $this->om->persist($c114);
        $this->om->persist($c115);
        $this->om->persist($c116);
        $this->om->persist($c117);

        $p1 = new Page();
        $p1->setTitle('Program');
        $p1->setCategory($c11);

        $p2 = new Page();
        $p2->setTitle('Partnerzy');
        $p2->setCategory($c11);

        $this->om->persist($p1);
        $this->om->persist($p2);
    }

    public function getOrder()
    {
        return 9;
    }
}