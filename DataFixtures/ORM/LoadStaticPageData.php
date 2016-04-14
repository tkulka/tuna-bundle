<?php

namespace TheCodeine\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use TheCodeine\GalleryBundle\Entity\GalleryItem;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\VideoBundle\Entity\Video;
use TheCodeine\ImageBundle\Entity\Image;

class LoadStaticPageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $pages = array('Contact', 'About');

        foreach ($pages as $title) {
            $this->createPage($om, $title);
        }

        $om->flush();
    }

    private function createPage($om, $title)
    {
        $page = new Page();
        $page->setTitle($title);
        $page->setBody('<p>Aenean mi ante, venenatis sed ullamcorper vel, rutrum pulvinar massa. Fusce metus mauris, feugiat ut leo a, hendrerit efficitur magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Suspendisse dictum ex non tortor elementum, sit amet gravida lectus blandit. Proin sed justo id libero placerat maximus. Fusce ut facilisis sapien. Praesent sed sapien id erat luctus pellentesque mollis et mi. Maecenas eget mollis purus, sit amet hendrerit massa. Sed eget vehicula nisl, ut vestibulum ex. Duis tincidunt purus id ex rhoncus feugiat.</p><p>Donec vel placerat ante. Nulla id enim ac neque tincidunt hendrerit. Cras pharetra massa eu mattis ultricies. Fusce pretium leo ut sem ultricies, sed dignissim eros iaculis. Nam maximus in nunc feugiat consequat. Sed ac urna mattis, commodo massa a, auctor massa. Proin pharetra aliquet dolor, id facilisis purus commodo non. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas iaculis ligula non mauris sagittis, ut vehicula ante aliquam. Etiam gravida, ligula a efficitur malesuada, eros velit cursus eros, eu vulputate dolor dolor eu sapien. Integer gravida lobortis diam vel pharetra. Aliquam malesuada tristique viverra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>');

        if ($title === 'About') {
            $gallery = new Gallery();

            $galleryItem1 = new GalleryItem();
            $galleryItem2 = new GalleryItem();

            $video = new Video();
            $video->setUrl('https://vimeo.com/139729395');
            $video->setVideoId('139729395');
            $video->setType('vimeo');

            $path = $this->container->get('kernel')->getRootDir() . '/../files/test.jpeg';
            $file = new File($path);

            $image = new Image();
            $image->setPath('test.path');
            $image->setFile($file);

            $galleryItem1->setType(0);
            $galleryItem1->setVideo($video);

            $galleryItem2->setType(1);
            $galleryItem2->setImage($image);

            $gallery->addItem($galleryItem1);
            $gallery->addItem($galleryItem2);
            $gallery->setTitle('Gallery in page');

            $page->setGallery($gallery);
        }

        $om->persist($page);
    }


    public function getOrder()
    {
        return 11;
    }

}