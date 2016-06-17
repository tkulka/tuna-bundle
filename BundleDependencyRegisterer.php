<?php

namespace TheCodeine\AdminBundle;

class BundleDependencyRegisterer
{
    static public function register(array &$bundles)
    {
        $dependencies = array(
            new \TheCodeine\AdminBundle\TheCodeineAdminBundle(),
            new \TheCodeine\EditorBundle\TheCodeineEditorBundle(),
            new \TheCodeine\PageBundle\TheCodeinePageBundle(),
            new \TheCodeine\ImageBundle\TheCodeineImageBundle(),
            new \TheCodeine\VideoBundle\TheCodeineVideoBundle(),
            new \TheCodeine\GalleryBundle\TheCodeineGalleryBundle(),
            new \TheCodeine\TagBundle\TheCodeineTagBundle(),
            new \TheCodeine\NewsBundle\TheCodeineNewsBundle(),
            new \TheCodeine\UserBundle\TheCodeineUserBundle(),
            new \TheCodeine\TranslationBundle\TheCodeineTranslationBundle(),
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
