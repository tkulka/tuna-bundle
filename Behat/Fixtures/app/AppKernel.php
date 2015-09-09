<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            # Symfony2 standard bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            # Self bundle dependencies
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            # ImageBundle
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),

            # NewsBundle
            new Vich\UploaderBundle\VichUploaderBundle(),

            new TheCodeine\EditorBundle\TheCodeineEditorBundle(),
            new TheCodeine\ImageBundle\TheCodeineImageBundle(),
            new TheCodeine\GalleryBundle\TheCodeineGalleryBundle(),
            new TheCodeine\NewsBundle\TheCodeineNewsBundle(),
            new TheCodeine\TagBundle\TheCodeineTagBundle(),

            # Self bundle
            new TheCodeine\AdminBundle\TheCodeineAdminBundle()
        );
    }

    /**
     * @return null
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
