<?php

namespace TheCodeine\GalleryBundle\Behat\Context;

use Behat\Mink\Mink;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit_Framework_Assert;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Tools\SchemaTool;

use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\GalleryBundle\Entity\GalleryItem;

use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\VideoBundle\Entity\Video;

class GalleryContext implements MinkAwareContext
{
    use KernelDictionary;

    private $mink;
    private $minkParameters;

    /**
     * Initializes context with parameters from behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Sets Mink instance.
     *
     * @param Mink $mink Mink session manager
     */
    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * Sets parameters provided for Mink.
     *
     * @param array $parameters
     */
    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        $metadata = $this->getMetadata();

        if (!empty($metadata)) {
            $tool = new SchemaTool($this->getEntityManager());
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    /**
     * @Given /^I have a kernel instance$/
     */
    public function iHaveAKernelInstance()
    {
        return is_a($this->kernel, 'Symfony\\Component\\HttpKernel\\KernelInterface');
    }

    /**
     * @Then /^the header "(?P<name>[^"]*)" should be equal to "(?P<value>[^"]*)"$/
     */
    public function theHeaderShouldBeEqualTo($name, $value)
    {
        $response = $this->mink->getSession()->getDriver()->getClient()->getResponse();
        PHPUnit_Framework_Assert::assertEquals($value, $response->getHeader($name));
    }

    /**
     * @return array
     */
    protected function getMetadata()
    {
        return $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Given /^there is a gallery:$/
     */
    public function thereIsAGallery(TableNode $table)
    {
        $em = $this->getEntityManager();
        foreach ($table->getHash() as $hash) {
            $path = $this->kernel->getRootDir() . '/../files/test.jpeg';
            $file = new File($path);

            $video = new Video();
            $video->setUrl('https://www.youtube.com/0erGiEm07b8');
            $video->setVideoId('0erGiEm07b8');
            $video->setType('youtube');

            $image = new Image();
            $image->setPath('test.path');
            $image->setFile($file);

            $galleryItem = new GalleryItem();
            $galleryItem->setImage($image);
            $galleryItem->setType(1);

            $galleryItem2 = new GalleryItem();
            $galleryItem2->setVideo($video);
            $galleryItem2->setType(0);

            $gallery = new Gallery();
            $gallery->addItem($galleryItem);
            $gallery->addItem($galleryItem2);
            $gallery->setTitle($hash['title']);
            $em->persist($gallery);
        }

        $em->flush();
    }

    /**
     * @When /^I add image to gallery "(?P<id>[^"]*)"$/
     */
    public function iAddImageToGallery($id)
    {
        $em = $this->getEntityManager();
        $gallery = $em->getRepository('TheCodeineGalleryBundle:Gallery')->find($id);

        if (!$gallery) {
            throw new NotFoundHttpException('No gallery found!');
        }

        $path = $this->kernel->getRootDir() . '/../files/test.jpeg';
        $file = new File($path);

        $image = new Image();
        $image->setPath('test.path');
        $image->setFile($file);

        $galleryItem = new GalleryItem();
        $galleryItem->setImage($image);
        $galleryItem->setType(1);

        $gallery->addItem($galleryItem);

        $em->persist($gallery);
        $em->flush();
    }

    /**
     * @When /^I add video to gallery "(?P<id>[^"]*)"$/
     */
    public function iAddVideoToGallery($id)
    {
        $em = $this->getEntityManager();
        $gallery = $em->getRepository('TheCodeineGalleryBundle:Gallery')->find($id);

        if (!$gallery) {
            throw new NotFoundHttpException('No gallery found!');
        }

        $video = new Video();
        $video->setUrl('https://www.youtube.com/0erGi2Em07b8');
        $video->setVideoId('0erGi2Em07b8');
        $video->setType('youtube');

        $galleryItem = new GalleryItem();
        $galleryItem->setVideo($video);
        $galleryItem->setType(0);

        $gallery->addItem($galleryItem);

        $em->persist($gallery);
        $em->flush();
    }
}
