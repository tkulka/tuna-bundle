# TheCodeine FileBundle

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