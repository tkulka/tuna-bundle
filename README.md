#TheCodeine AdminBundle

##Installation:
  1. Require module:
  
        composer require "thecodeine/tuna-adminbundle": "dev-master"
  2. Register bundles:
  
    Add following line to `AppKernel::registerBundles()`
    
        `TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);`
  3. Migrate db:
   
    Via `doctrine:migrations:diff && doctrine:migrations:migrate` or `doctrine:schema:update`
    
  4. Add routing
    
        # app/config/routing.yml
        
        the_codeine_tuna_admin:
            resource: "@TheCodeineAdminBundle/Resources/config/routing.yml"
  5. Override config
  
    Tuna injects some basic configs, but feel free to override them (be aware that you can broke some of functionality by this).  
    For newest config defaults check [Resources/config/config.yml](Resources/config/config/yml)
