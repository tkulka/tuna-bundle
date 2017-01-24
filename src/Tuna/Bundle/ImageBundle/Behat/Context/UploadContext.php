<?php

namespace TheCodeine\ImageBundle\Behat\Context;

use Behat\Mink\Mink;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit_Framework_Assert;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Tools\SchemaTool;

use TheCodeine\ImageBundle\Entity\Image;


class UploadContext implements MinkAwareContext
{
    use KernelDictionary;

    private $mink;
    private $minkParameters;

    /**
     * Initializes context with parameters from behat.yml.
     */
    public function __construct() {}

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
     * @Then /^I should have file in uploads dir$/
     */
    public function iShouldHaveFileInUploadsDir()
    {
        $em = $this->getEntityManager();
        $images = $em->getRepository('TheCodeineImageBundle:Image')->findAll();
        $basePath = $this->getKernel()->getRootDir() . '/../web/uploads/';

        foreach ($images as $image) {
            new File($basePath . $image->getPath());
        }
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