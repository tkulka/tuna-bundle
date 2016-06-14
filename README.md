#TheCodeine AdminBundle

##Installation:
  * `composer require "thecodeine/tuna-adminbundle": "dev-master"`
  * add `TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);` to `AppKernel::registerBundles()`
  * migrate db: via doctrine migrations or `doctrine:schema:update`
  * add some config and routing
    
        # app/config/routing.yml
        
        the_codeine_tuna_admin:
            resource: "@TheCodeineAdminBundle/Resources/config/routing.yml"
    and:
        
        # app/config/config.yml
        imports:
        - { resource: "@TheCodeineAdminBundle/Resources/config/config.yml" } #this will add liip_imagine config
