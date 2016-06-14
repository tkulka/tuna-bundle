#TheCodeine AdminBundle

##Installation:
  * `composer require "thecodeine/tuna-adminbundle": "dev-master"`
  * add `TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);` to `AppKernel::registerBundles()`
  * migrate db: via doctrine migrations or `doctrine:schema:update`
  * add some config and routing - still working on config - wait for update ;)
    
        # app/config/routing.yml
        
        the_codeine_tuna_admin:
            resource: "@TheCodeineAdminBundle/Resources/config/routing.yml"
    and:
        
        # app/config/config.yml
        liip_imagine:
            driver: gd
            resolvers:
               default:
                  web_path: ~
            filter_sets:
               admin_thumb:
                      quality: 90
                      filters:
                          thumbnail: { size: [260, 180], mode: outbound }
               gallery_thumb:
                      quality: 90
                      filters:
                          thumbnail: { size: [120, 90], mode: outbound }
               article_thumb:
                      quality: 90
                      filters:
                          thumbnail: { size: [200, 100], mode: outbound }
               article_full:
                      quality: 90
                      filters:
                          thumbnail: { size: [500, 800], mode: inset }
               gallery_thumb:
                      quality: 90
                      filters:
                          thumbnail: { size: [160, 120], mode: outbound }
               gallery_full:
                      quality: 90
                      filters:
                          thumbnail: { size: [1900, 1200], mode: inset }
               newsThumb:
                    quality: 90
                    filters:
                        relative_resize: { widen: 170 }
