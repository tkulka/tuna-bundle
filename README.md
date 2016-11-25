# TheCodeine AdminBundle

## Installation:

  1. Add to `composer.json`:
                  
        "repositories": [
            {
                "type": "vcs",
                "url": "git@bitbucket.org:thecodeine/tuna-adminbundle.git"
            }
        ],
        
  2. Require module:
  
        composer require "thecodeine/tuna-adminbundle": "dev-master"  
            
  3. Register bundles:
  
    Add following line to `AppKernel::registerBundles()`
    
        TheCodeine\AdminBundle\BundleDependencyRegisterer::register($bundles);
        
  4. Migrate db:
   
        doctrine:migrations:diff && doctrine:migrations:migrate
    
  5. Add routing
    
        # app/config/routing.yml
        
        the_codeine_tuna_admin:
            resource: "@TheCodeineAdminBundle/Resources/config/routing.yml"
            
  6. Override config
  
    Tuna injects some basic configs, but feel free to override them (be aware that you can broke some of functionalities by this).  
    For newest config defaults check [Resources/config/config.yml](Resources/config/config.yml). This file also includes
    [Resources/config/security.yml](Resources/config/security.yml), so be sure to clear your security.yml file, or override some parts.
      
    You can also fine tune Tuna by changing bundle config.  
    Here is full option config with defaults:
    
        the_codeine_admin:
             paths:
                 admin_logo: bundles/thecodeineadmin/images/logo.png
                 cke_config: bundles/thecodeineeditor/js/editorConfig.js
             host: null
             menu_builder: TheCodeine\AdminBundle\Menu\Builder
             locales: [en,pl]
             components:
                 pages:
                     enabled: true
                     create: true
                     delete: true
                 editor:
                     wysiwyg_style_dir: '%kernel.root_dir%/../vendor/thecodeine/tuna-adminbundle/Resources/public/sass/editor'
                 menu:
                     enabled: true
                     default_template: 'TheCodeineMenuBundle:Menu:render_menu.html.twig'
                 news:
                     enabled: true
                 events:
                     enabled: false
                 translations:
                     enabled: true
                 categories:
                     enabled: false
                 security:
                     enabled: true
                     use_access_control: true
                 

## Frontend translations:

Translations are enabled by default. You can turn them off by setting:

        the_codeine_admin:
            components:
                translations: 
                    enabled: false

Dump translation files:

    php app/console translation:extract [language] --dir=[directory] --output-dir=./app/Resources/translations
Replace `[language]` with any language you want to generate translations for (e.g. `de`) and `[directory]` with path to your bundle (e.g. `./src/Openheim/FrontendBundle`).

## Admin JS translations:

JavaScript translations are in `Resources/translations/tuna_admin.pl.yml`. To cache translations, run this command:

    php app/console bazinga:js-translation:dump

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

## Overriding Tuna

### General

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

### Menu

Add `menu_builder` class to config, and override, or use your own Builder:

    class Builder extends \TheCodeine\AdminBundle\Menu\Builder
    {
        protected function buildTopMenu(Request $request)
        {
            $menu = parent::buildTopMenu($request);
            /**
             * use custom position to inject your pages between standard links, e.g.:
             *      Pages: 100
             *      News: 110
             *      Translations: 500
             */
            $this->addChild($menu, $request, 'Projects', 'tuna_page_list', 90, function ($request, $route) {
                return preg_match_all('/app_project_/i', $request->get('_route'));
            });
    
            return $menu;
        }
    }

### Editor styles

To override default editor styles, add `typography.scss` and `fonts.scss` to directory specified in `the_codeine_admin.components.editor.wysiwyg_style_dir` (remember to change it in your config). Example:
    
    the_codeine_admin:
        components:
            editor:
                wysiwyg_style_dir: "%kernel.root_dir%/../src/AppBundle/Resources/public/Frontend/sass/base/"

## File type

### Using and extending

Tuna provides two types of files: `File` and `Image`. You can easily add yours by extending `AbstractFile` entity and `AbstractFileType` form.

You can use these types in entity as:

  1. required field:
    
        // AppBundle/Entity/Project.php
         
        use TheCodeine\FileBundle\Validator\Constraints as FileAssert;  
         
        /**
         * @var File
         *
         * @FileAssert\FileNotNull
         * @ORM\ManyToOne(targetEntity="TheCodeine\FileBundle\Entity\File", cascade={"persist", "remove"})
        **/
        protected $file;
         
         
        // AppBundle/Form/ProjectType.php
         
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('image', ImageType::class);
        }
        
  2. optional field (you can delete file by setting empty `path`):
  
        // AppBundle/Entity/Project.php
         
        /**
         * @ORM\OneToOne(targetEntity="TheCodeine\FileBundle\Entity\Image", cascade={"persist", "remove"})
         * @ORM\JoinColumn(onDelete="SET NULL")
         */
        protected $image;
         
         
        // AppBundle/Form/ProjectType.php
         
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('image', ImageType::class, array(
                'attr' => array('deletable' => false), // defaults to true
            );
        }

You can change default file location via `the_codeine_file` config (here's the defaults):

    the_codeine_file:
        file_manager:
            web_root_dir: '%kernel.root_dir%/../web'
            tmp_path: uploads/tmp
            upload_files_path: uploads/files

#### Rendering

FileBundle provides three twig helpers for easy file rendering:

  * `tuna_image(AbstractFile, filter = null)` - generates assets path to image, additionally you can apply imagine filter:
    
        <img src="{{ tuna_image(news.image, 'news_thumb') }}">
        
  * `tuna_file(AbstractFile)` - generates assets path to file:
    
        <a href="{{ tuna_file(attachment.file) }}"></a>`
    
  * `tuna_uploadDir(type)` - returns path to upload dir of given file (where type is `tmp_path|upload_files_path`), useful for placeholders:
    
        previewTemplate: theme.tuna_image_preview(tuna_uploadDir('tmp_path')~'/__path__', form.vars.attr.deletable)


## Menu

  1. Add root menu item to migration/fixture:  

        $this->addSql('INSERT INTO menu (id, root_id, label, clickable, publish_date, lft, rgt) VALUES(1, 1, "Menu", 1, "2016-09-12 13:00:00", 1, 2)');

  2. Add action to your frontend controller (remember to add this route at the end):
  
        /**
         * @Route("/{slug}", requirements={"slug"=".+"}, name="tuna_menu_item")
         * @Template()
         */
        public function pageAction(Menu $menu)
        {
            if (!$menu->isPublished() || !$menu->getPage()) {
                throw new NotFoundHttpException();
            }
    
            return array(
                'page' => $menu->getPage(),
            );
        }

  3. Render your menu in template:
  
        {{ tuna_menu_render() }}
        
    You can also use full syntax:
    
        {{ tuna_menu_render('Label', {
            template: 'default/partials/render_menu.html.twig',
            wrap: false 
        }) }}
    
    * `Label` - label of the root menu item (defaults to `Menu`) 
    * `template` - custom menu template (defaults to `'TheCodeineMenuBundle:Menu:render_menu.html.twig'`). You can also set default template in config.
    * `wrap` - whether wrap menu in `<ul>` tags or not (defaults to true)
