<?php

namespace TunaCMS\Bundle\CategoryBundle\Tests\Form;

use A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener;
use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsFieldsType;
use A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\CategoryBundle\Entity\Category;
use TunaCMS\Bundle\CategoryBundle\Form\CategoryType;
use TunaCMS\Bundle\CategoryBundle\Entity\CategoryTranslation;

class CategoryTypeTest extends TypeTestCase
{
    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var GedmoTranslationsListener
     */
    private $translationsListener;

    /**
     * @var GedmoTranslationForm
     */
    private $translationForm;

    protected function setUp()
    {
        $this->translatableListener = $this->createMock(TranslatableListener::class);
        $this->translationForm = $this->getMockBuilder(GedmoTranslationForm::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getGedmoTranslatableListener',
                    'getTranslatableFields',
                    'getChildrenOptions',
                    'getTranslationClass',
                ]
            )
            ->getMock()
        ;
        $this->translationForm
            ->method('getGedmoTranslatableListener')
            ->will($this->returnValue($this->translatableListener))
        ;
        $this->translationForm
            ->method('getTranslatableFields')
            ->will($this->returnValue([]))
        ;
        $this->translationForm
            ->method('getChildrenOptions')
            ->will(
                $this->returnValue(
                    [
                        'en' => [
                            'name' => [
                                'required' => true,
                                'field_type' => TextType::class,
                            ],
                        ],
                    ]
                )
            )
        ;
        $this->translationForm
            ->method('getTranslationClass')
            ->will($this->returnValue(CategoryTranslation::class))
        ;

        $this->translationsListener = new GedmoTranslationsListener($this->translationForm);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $translationsType = new GedmoTranslationsType(
            $this->translationsListener,
            $this->translationForm,
            ['en'],
            true
        );

        $translationsFieldsType = new TranslationsFieldsType();

        return [
            new PreloadedExtension(
                [
                    $translationsType,
                    $translationsFieldsType,
                ], []
            ),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'translations' => [
                'en' => [
                    'name' => 'foo',
                ],
            ],
        ];

        $translationObject = new CategoryTranslation();
        $translationObject
            ->setLocale('en')
            ->setField('name')
            ->setContent('foo')
        ;

        $object = new Category();
        $object->addTranslation($translationObject);

        $form = $this->factory->create(CategoryType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
