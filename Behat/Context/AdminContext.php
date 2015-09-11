<?php

namespace TheCodeine\AdminBundle\Behat\Context;

use Behat\Mink\Mink;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit_Framework_Assert;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\Tools\SchemaTool;

use TheCodeine\PageBundle\Entity\Page;

class AdminContext implements MinkAwareContext
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
     * @Given /^I can get "(?P<repositoryName>[^"]+)" repository$/
     */
    public function iCanGetRepository($repositoryName)
    {
        return is_a($this->getEntityManager()->getRepository("TheCodeinePageBundle:" . $repositoryName), 'TheCodeine\\PageBundle\\Repository');
    }

    /**
     * @Given /^I should have editor extension service in container$/
     */
    public function iShouldHaveEditorExtensionServiceInContainer()
    {
        return is_a($this->getContainer()->get('thecodeine_editor.twig.form_extension'), 'TheCodeine\EditorBundle\Twig\Extension\EditorExtension');
    }

    /**
     * @Given /^There is a page:$/
     */
    public function thereIsANews(TableNode $table)
    {
        $em = $this->getEntityManager();

        $hash = $table->getHash();
        foreach ($hash as $row) {
            if (!isset($row['title']) || !isset($row['slug']) || !isset($row['body'])) {
                throw new \Exception("You must provide a 'title', 'slug' and 'body' column in your table node.");
            }

            $news = new Page();
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