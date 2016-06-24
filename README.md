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
  
    Tuna injects some basic configs, but feel free to override them (be aware that you can broke some of functionalities by this).  
    For newest config defaults check [Resources/config/config.yml](Resources/config/config.yml). This file also includes
    [Resources/config/security.yml](Resources/config/security.yml), so be sure to clear your security.yml file, or override some parts.
    
    You can also fine tune Tuna by changing bundle config.  
    Here is full option config with defaults:
    
        the_codeine_admin:
            paths:
                admin_logo: bundles/thecodeineadmin/images/logo.png
            enable_translations: true

##Translations:
Translations are enabled by default. You can turn them off by overriding `the_codeine_admin.enable_translations` config.

Dump translation files:

    php app/console translation:extract de --dir=./src/ --output-dir=./app/Resources/translations
Replace `de` with any language you want to generate translations for.
