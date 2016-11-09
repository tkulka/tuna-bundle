<?php

namespace TheCodeine\Tests\DependencyInjection;

use A2lix\TranslationFormBundle\A2lixTranslationFormBundle;
use A2lix\TranslationFormBundle\DependencyInjection\A2lixTranslationFormExtension;
use Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle;
use Bazinga\Bundle\JsTranslationBundle\DependencyInjection\BazingaJsTranslationExtension;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use FOS\UserBundle\DependencyInjection\FOSUserExtension;
use FOS\UserBundle\FOSUserBundle;
use JMS\TranslationBundle\DependencyInjection\JMSTranslationExtension;
use JMS\TranslationBundle\JMSTranslationBundle;
use Knp\Bundle\GaufretteBundle\DependencyInjection\KnpGaufretteExtension;
use Knp\Bundle\GaufretteBundle\KnpGaufretteBundle;
use Knp\Bundle\PaginatorBundle\DependencyInjection\KnpPaginatorExtension;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Liip\ImagineBundle\DependencyInjection\LiipImagineExtension;
use Liip\ImagineBundle\LiipImagineBundle;
use SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle;
use SmartCore\Bundle\AcceleratorCacheBundle\DependencyInjection\AcceleratorCacheExtension;
use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\AsseticBundle\AsseticBundle;
use Symfony\Bundle\AsseticBundle\DependencyInjection\AsseticExtension;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TheCodeine\AdminBundle\DependencyInjection\TheCodeineAdminExtension;
use TheCodeine\EditorBundle\DependencyInjection\TheCodeineEditorExtension;
use TheCodeine\EditorBundle\TheCodeineEditorBundle;
use Vich\UploaderBundle\DependencyInjection\VichUploaderExtension;
use Vich\UploaderBundle\VichUploaderBundle;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function testEmptyConfig()
    {
        $container = $this->getRawContainer();
        $loader = new TheCodeineAdminExtension();
        $config = $this->getEmptyConfig();
        $loader->prepend($container);
        $loader->load(array($config), $container);
    }

    public function testLoadSecurityComponentSecurityEnabledAndUseAccessControl()
    {
        $this->container = $this->getRawContainer();
        $loader = new TheCodeineAdminExtension();
        $loader->prepend($this->container);

        $this->assertHasResource('config/security.yml');
        $this->assertHasResource('config/access_control.yml');
    }

    public function testLoadSecurityComponentSecurityDisabled()
    {
        $this->container = $this->getRawContainer();
        $loader = new TheCodeineAdminExtension();
        $config = $this->getEmptyConfig();
        $config['components']['security']['enabled'] = false;
        $this->container->prependExtensionConfig($loader->getAlias(), $config);
        $loader->prepend($this->container);

        $this->assertNotHasResource('config/security.yml');
        $this->assertNotHasResource('config/access_control.yml');
    }

    public function testLoadSecurityComponentSecurityEnabledAndNotUseAccessControl()
    {
        $this->container = $this->getRawContainer();
        $loader = new TheCodeineAdminExtension();
        $config = $this->getEmptyConfig();
        $config['components']['security']['use_access_control'] = false;
        $this->container->prependExtensionConfig($loader->getAlias(), $config);
        $loader->prepend($this->container);

        $this->assertHasResource('config/security.yml');
        $this->assertNotHasResource('config/access_control.yml');
    }

    /**
     * @param string $file
     */
    private function assertHasResource($file)
    {
        $resources = $this->container->getResources();
        $path = realpath(__DIR__ . '/../../Resources/' . $file);

        $this->assertContains($path, $resources);
    }

    /**
     * @param string $file
     */
    private function assertNotHasResource($file)
    {
        $resources = $this->container->getResources();
        $path = realpath(__DIR__ . '/../../Resources/' . $file);

        $this->assertNotContains($path, $resources);
    }

    /**
     * getEmptyConfig.
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        return array();
    }

    /**
     * @return ContainerBuilder
     */
    private function getRawContainer()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.root_dir', __DIR__ . '/..');

        $extensions = [
            new SecurityExtension(),
            new FOSUserExtension(),
            new A2lixTranslationFormExtension(),
            new TheCodeineEditorExtension(),
            new KnpPaginatorExtension(),
            new KnpGaufretteExtension(),
            new VichUploaderExtension(),
            new JMSTranslationExtension(),
            new LiipImagineExtension(),
            new BazingaJsTranslationExtension(),
            new AcceleratorCacheExtension(),
            new AsseticExtension(),
            new DoctrineExtension(),
            new StofDoctrineExtensionsExtension(),
        ];

        foreach ($extensions as $extension) {
            $container->registerExtension($extension);
        }

        $bundles = [
            new SecurityBundle(),
            new FOSUserBundle(),
            new A2lixTranslationFormBundle(),
            new TheCodeineEditorBundle(),
            new KnpPaginatorBundle(),
            new KnpGaufretteBundle(),
            new VichUploaderBundle(),
            new JMSTranslationBundle(),
            new LiipImagineBundle(),
            new BazingaJsTranslationBundle(),
            new AcceleratorCacheBundle(),
            new AsseticBundle(),
            new StofDoctrineExtensionsBundle(),
        ];

        foreach ($bundles as $bundle) {
            $bundle->build($container);
        }

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        return $container;
    }
}