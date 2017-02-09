Tuna CMS FileBundle

[![Latest Stable Version](https://poser.pugx.org/tuna-cms/file-bundle/v/stable)](https://packagist.org/packages/tuna-cms/file-bundle)
[![Total Downloads](https://poser.pugx.org/tuna-cms/file-bundle/downloads)](https://packagist.org/packages/tuna-cms/file-bundle)
[![License](https://poser.pugx.org/tuna-cms/file-bundle/license)](https://packagist.org/packages/tuna-cms/file-bundle)

## Standalone usage:
1. Enable FOSJsRoutingBundle:

        <?php
        // app/AppKernel.php

        // ...
        class AppKernel extends Kernel
        {
            public function registerBundles()
            {
                $bundles = array(
                    // ...

                    new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
                );

                // ...
            }

            // ...
        }

2. Register the Routes:

        # app/config/routing.yml
        fos_js_routing:
            resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.xml"