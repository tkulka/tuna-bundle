<?php

namespace TheCodeine\AdminBundle;

class BundleDependencyRegisterer
{
    static public function register(array &$bundles)
    {
        $dependencies = array(
            new \TheCodeine\AdminBundle\TheCodeineAdminBundle(),
            new \TheCodeine\CategoryBundle\TheCodeineCategoryBundle(),
            new \TheCodeine\EditorBundle\TheCodeineEditorBundle(),
            new \TheCodeine\FileBundle\TheCodeineFileBundle(),
            new \TheCodeine\PageBundle\TheCodeinePageBundle(),
            new \TheCodeine\VideoBundle\TheCodeineVideoBundle(),
            new \TheCodeine\GalleryBundle\TheCodeineGalleryBundle(),
            new \TheCodeine\TagBundle\TheCodeineTagBundle(),
            new \TheCodeine\NewsBundle\TheCodeineNewsBundle(),
            new \TheCodeine\MenuBundle\TheCodeineMenuBundle(),
            new \TheCodeine\UserBundle\TheCodeineUserBundle(),
            new \TheCodeine\TranslationBundle\TheCodeineTranslationBundle(),
            new \SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle(),
            new \JMS\TranslationBundle\JMSTranslationBundle(),
            new \Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new \A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Vich\UploaderBundle\VichUploaderBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        );

        foreach ($bundles as $bundle) {
            foreach ($dependencies as $k => $dependency) {
                if ($bundle instanceof $dependency) {
                    unset($dependencies[$k]);
                }
            }
        }

        $bundles = array_merge($bundles, $dependencies);
    }
}
