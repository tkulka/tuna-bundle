#TheCodeine AdminBundle

##Installation:
  * `composer require "thecodeine/tuna-adminbundle": "dev-master"`
  * add `TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);` to `AppKernel::registerBundles()`
  * migrate db: via doctrine migrations or `doctrine:schema:update`
  * add some config and routing - still working on this - wait for update ;)
    for now it's something like this:
    
        # app/config/routing.yml
        
        the_codeine_news:
            resource: "@TheCodeineNewsBundle/Resources/config/routing.yml"
        
        the_codeine_page:
            resource: "@TheCodeinePageBundle/Resources/config/routing.yml"
        
        the_codeine_admin:
            resource: "@TheCodeineAdminBundle/Resources/config/routing.yml"
        
        thecodeine_image:
            resource: "@TheCodeineImageBundle/Resources/config/routing.yml"
        
        the_codeine_user:
            resource: "@TheCodeineUserBundle/Controller/"
            type:     annotation
            prefix:   /
        
        _liip_imagine:
            resource: "@LiipImagineBundle/Resources/config/routing.xml"
        
        # SECURITY
        fos_user_security_login:
            path: /login
            defaults: { _controller: TheCodeineAdminBundle:Security:login }
        
        fos_user_security_check:
            path: /login_check
            defaults: { _controller: TheCodeineAdminBundle:Security:check }
            methods:  [POST]
        
        fos_user_security_logout:
            path: /logout
            defaults: { _controller: TheCodeineAdminBundle:Security:logout }
    and:
        
        # app/config/config.yml
        the_codeine_tag: ~
        
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
