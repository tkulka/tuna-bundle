<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\FileBundle\Entity\Attachment;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $news = array(
            'category' => array('Tuna is a saltwater finfish'),
            'gallery' => array('News with gallery'),
            'attachment' => array('News with attachment')
        );

        foreach ($news as $branch => $titles) {
            foreach ($titles as $title) {
                $this->createNews($om, $title, $branch);
            }
        }

        $om->flush();
    }

    private function createNews($om, $name, $branch)
    {
        $news = new News();
        $news->setTitle($name);
        $news->setBody('<p>A tuna is a saltwater finfish that belongs to the tribe Thunnini, a sub-grouping of the mackerel family (Scombridae) – which together with the tunas, also includes the bonitos, mackerels, and Spanish mackerels. Thunnini comprises fifteen species across five genera,[1] the sizes of which vary greatly, ranging from the bullet tuna (max. length: 50 cm (1.6 ft), weight: 1.8 kg (4 lb)) up to the Atlantic bluefin tuna (max. length: 4.6 m (15 ft), weight: 684 kg (1,508 lb)). The bluefin averages 2 m (6.6 ft), and is believed to live for up to 50 years.'.
                       'Tuna and mackerel sharks are the only species of fish that can maintain a body temperature higher than that of the surrounding water. An active and agile predator, the tuna has a sleek, streamlined body, and is among the fastest-swimming pelagic fish – the yellowfin tuna, for example, is capable of speeds of up to 75 km/h (47 mph).[2] Found in warm seas, it is extensively fished commercially, and is popular as a game fish. As a result of over-fishing, stocks of some tuna species such as the southern bluefin tuna have been reduced dangerously close to the point of extinction.</p>');
        $news->setTeaser('A tuna is a saltwater finfish that belongs to the tribe Thunnini, a sub-grouping of the mackerel family (Scombridae)');

        $path = $this->container->get('kernel')->locateResource('@TheCodeineAdminBundle/DataFixtures/img') . '/tuna.jpg';

        // FIXME new attachment handling
        if ($branch === 'attachmentIsNotSupportedYet') {

            $path = $this->container->get('kernel')->locateResource('@TheCodeineAdminBundle/DataFixtures/img') . '/tuna.jpg';
            $file = new File($path);

            $attachment = new Attachment();
            $attachment->setFile($file);
            $attachment->setFileName('Attachment_name');
            $attachment->setTitle('Attachment title');

            $news->addAttachment($attachment);

        }

        $om->persist($news);

    }


    public function getOrder()
    {
        return 10;
    }

}
