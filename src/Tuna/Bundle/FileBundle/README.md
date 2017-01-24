Tuna CMS FileBundle

[ ![Codeship Status for Tuna-CMS/file-bundle](https://app.codeship.com/projects/bbd21400-c125-0134-7fc8-7ee917b55fa1/status?branch=master)](https://app.codeship.com/projects/197169)

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