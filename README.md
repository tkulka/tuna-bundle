# TheCodeine AdminBundle

## Installation:
  1. Require module:
  
        composer require "thecodeine/tuna-adminbundle": "dev-master"
  2. Register bundles:
  
    Add following line to `AppKernel::registerBundles()`
    
        TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);
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

## Translations:
Translations are enabled by default. You can turn them off by overriding `the_codeine_admin.enable_translations` config.

Dump translation files:

    php app/console translation:extract [language] --dir=[directory] --output-dir=./app/Resources/translations
Replace `[language]` with any language you want to generate translations for (e.g. `de`) and `[directory]` with path to your bundle (e.g. `./src/Openheim/FrontendBundle`).

## Extending Page:
  1. Entity:
  
        use TheCodeine\PageBundle\Entity\AbstractPage;
        
        /**
         * Subpage
         *
         * @ORM\Table(name="subpage")
         * @Gedmo\TranslationEntity(class="AppBundle\Entity\SubpageTranslation")
         * @ORM\Entity(repositoryClass="TheCodeine\PageBundle\Entity\PageRepository") // or extend this one
         */
        class Subpage extends AbstractPage
        {
            /**
             * @ORM\OneToMany(targetEntity="SubpageTranslation", mappedBy="object", cascade={"persist", "remove"})
             */
            protected $translations;
        }

  2. Form:
  
        use TheCodeine\PageBundle\Form\AbstractPageType;
        
        class SubpageType extends AbstractPageType
        {
            /**
             * @param FormBuilderInterface $builder
             * @param array $options
             */
            public function buildForm(FormBuilderInterface $builder, array $options)
            {
                parent::buildForm($builder, $options);
                // your additional fields
            }
        
            protected function getEntityClass()
            {
                return 'AppBundle\Entity\Subpage';
            }
        
            /**
             * @return string
             */
            public function getName()
            {
                return 'appbundle_subpage';
            }
        }

  3. Controller:
  
        use TheCodeine\PageBundle\Controller\AbstractPageController;
        use TheCodeine\PageBundle\Entity\AbstractPage;
        
        /**
         * Subpage controller.
         */
        class SubpageController extends AbstractPageController
        {
            public function getNewPage()
            {
                return new Subpage();
            }
        
            public function getNewFormType(BasePage $page = null, $validate = true)
            {
                return new SubpageType($validate);
            }
        
            public function getRedirectUrl(BasePage $page = null)
            {
                return $this->generateUrl('app_subpage_list');
            }
        
            public function getRepository()
            {
                return $this->getDoctrine()->getRepository('AppBundle:Subpage');
            }
        }
   
   4. Routing:
    Routes should be configured as annotations
    
        my_admin:
            resource: "@MyAdminBundle/Controller/SubpageController.php" # (or with Controller wildcard: "@MyAdminBundle/Controller/")
            type:     annotation
            prefix:   /admin/

## Global overriding Tuna elements

Use bundle overriding:

        namespace My\AdminBundle;
        
        use Symfony\Component\HttpKernel\Bundle\Bundle;
        
        class MyAdminBundle extends Bundle
        {
            public function getParent()
            {
                return 'TheCodeineAdminBundle';
            }
        }
