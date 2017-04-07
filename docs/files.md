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
            $builder->add('image', ImageType::class, [
                // set it to `false` to disable scaling of image
                'image_filter' => 'some_filter_name', // defaults to 'tuna_admin_thumb'
            ]);
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
            $builder->add('image', ImageType::class, [
                'attr' => [
                    'deletable' => false
                ], // defaults to true
                
                // set it to `false` to disable scaling of image
                'image_filter' => 'some_filter_name', // defaults to 'tuna_admin_thumb'
            ];
        }

You can change default file location via `the_codeine_file` config (here's the defaults):

    the_codeine_file:
        file_manager:
            web_root_dir: '%kernel.root_dir%/../web' # path to symfony's web directory
            tmp_path: uploads/tmp
            upload_files_path: uploads/files

#### Custom data loader/cache resolver
To avoid conflicts with other bundles using Liip Imagine as image processing engine, Tuna uses custom data loader/cache resolver: `tuna`.

#### Custom filters
To add your custom filters to use with tuna images make sure that you've added `data_loader` option to filter configuration:

    liip_imagine:
        filter_sets:
            person_thumb:
                data_loader: tuna # you can drop this if no other bundle override liip_imagine default loader/resolver.
                cache: tuna # you can drop this if no other bundle override liip_imagine default loader/resolver.
                filters:
                    .. your filters

#### Rendering
FileBundle provides three twig helpers for easy file rendering:

* `tuna_image(AbstractFile, filter = null)` - generates assets path to image, additionally you can apply imagine filter:

        <img src="{{ tuna_image(news.image, 'news_thumb') }}">

* `tuna_file(AbstractFile)` - generates assets path to file:

        <a href="{{ tuna_file(attachment.file) }}"></a>

* `tuna_uploadDir(type)` - returns path to upload dir of given file (where type is `tmp_path|upload_files_path`), useful for placeholders:

        previewTemplate: theme.tuna_image_preview(tuna_uploadDir('tmp_path')~'/__path__', form.vars.attr.deletable, image_filter)
