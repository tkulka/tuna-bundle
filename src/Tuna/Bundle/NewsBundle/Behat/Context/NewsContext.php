<?php

namespace TheCodeine\NewsBundle\Behat\Context;

use Behat\Mink\Mink;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit_Framework_Assert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\Tools\SchemaTool;

use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\FileBundle\Entity\Attachment;
use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\GalleryBundle\Entity\GalleryItem;
use TheCodeine\FileBundle\Entity\Image;

class NewsContext implements MinkAwareContext
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
     * @Given /^I can get "(?P<repositoryName>[^"]+)" repository$/
     */
    public function iCanGetRepository($repositoryName)
    {
        return is_a($this->getEntityManager()->getRepository("TheCodeineNewsBundle:" . $repositoryName), 'TheCodeine\\NewsBundle\\Repository');
    }

    /**
     * @Given /^There is a news:$/
     */
    public function thereIsANews(TableNode $table)
    {
        $em = $this->getEntityManager();

        $hash = $table->getHash();
        foreach ($hash as $row) {
            if (!isset($row['title']) || !isset($row['subTitle']) || !isset($row['body']) || !isset($row['slug'])) {
                throw new \Exception("You must provide a 'title', 'subTitle' and 'body' column in your table node.");
            }

            $news = new News();
            $news->setTitle($row['title']);
            $news->setSubTitle($row['subTitle']);
            $news->setBody($row['body']);
            $news->setSlug($row['slug']);

            $em->persist($news);
        }

        $em->flush();
    }

    /**
     * @Given /^There is a news with attachment and gallery:$/
     */
    public function thereIsANewsWithAttachmentAndGallery(TableNode $table)
    {
        $em = $this->getEntityManager();

        $hash = $table->getHash();
        foreach ($hash as $row) {
            if (!isset($row['title']) || !isset($row['slug']) || !isset($row['body'])) {
                throw new \Exception("You must provide a 'title', 'slug' and 'body' column in your table node.");
            }

            $news = new News();

            $attachment = new Attachment();
            $attachment->setFile('test.file');
            $attachment->setFileName('test.title');
            $attachment->setTitle('test.title');
            $news->addAttachment($attachment);

            $path = $this->kernel->getRootDir() . '/../files/test.jpeg';
            $file = new File($path);

            $image = new Image();
            $image->setPath('test.path');
            $image->setFile($file);

            $galleryItem = new GalleryItem();
            $galleryItem->setImage($image);

            $gallery = new Gallery();
            $gallery->addItem($galleryItem);
            $news->setGallery($gallery);

            $news->setTitle($row['title']);
            $news->setBody($row['body']);
            $news->setSlug($row['slug']);

            $em->persist($news);
        }

        $em->flush();
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
}
