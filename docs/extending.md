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