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
        $news = array(
            'category' => array('Hello world')
        );

        foreach ($news as $branch => $titles) {
            foreach ($titles as $title) {
                $this->createPage($om, $title, $branch);
            }
        }

        $om->flush();
    }

    private function createPage($om, $name, $branch)
    {
        $news = new News();
        $news->setTitle($name);
        $news->setBody('<p>Aenean mi ante, venenatis sed ullamcorper vel, rutrum pulvinar massa. Fusce metus mauris, feugiat ut leo a, hendrerit efficitur magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Suspendisse dictum ex non tortor elementum, sit amet gravida lectus blandit. Proin sed justo id libero placerat maximus. Fusce ut facilisis sapien. Praesent sed sapien id erat luctus pellentesque mollis et mi. Maecenas eget mollis purus, sit amet hendrerit massa. Sed eget vehicula nisl, ut vestibulum ex. Duis tincidunt purus id ex rhoncus feugiat.</p><p>Donec vel placerat ante. Nulla id enim ac neque tincidunt hendrerit. Cras pharetra massa eu mattis ultricies. Fusce pretium leo ut sem ultricies, sed dignissim eros iaculis. Nam maximus in nunc feugiat consequat. Sed ac urna mattis, commodo massa a, auctor massa. Proin pharetra aliquet dolor, id facilisis purus commodo non. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas iaculis ligula non mauris sagittis, ut vehicula ante aliquam. Etiam gravida, ligula a efficitur malesuada, eros velit cursus eros, eu vulputate dolor dolor eu sapien. Integer gravida lobortis diam vel pharetra. Aliquam malesuada tristique viverra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>');
        $news->setCategory($this->getReference($branch));
        $om->persist($news);
    }


    public function getOrder()
    {
        return 10;
    }

}