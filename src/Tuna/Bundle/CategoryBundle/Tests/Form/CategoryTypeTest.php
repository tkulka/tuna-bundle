<?php

namespace TunaCMS\Bundle\CategoryBundle\Tests\Form;

use A2lix\TranslationFormBundle\Form\EventListener\TranslationsListener;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use A2lix\TranslationFormBundle\Locale\LocaleProviderInterface;
use A2lix\TranslationFormBundle\TranslationForm\TranslationForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\CategoryBundle\Entity\Category;
use TunaCMS\Bundle\CategoryBundle\Form\CategoryType;
use TunaCMS\Bundle\CategoryBundle\Entity\CategoryTranslation;

class CategoryTypeTest extends TypeTestCase
{
    /**
     * @var TranslationsListener
     */
    private $translationsListener;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var TranslationForm
     */
    private $translationForm;

    protected function setUp()
    {
        $this->localeProvider = $this->createMock(LocaleProviderInterface::class);
        $this->localeProvider
            ->method('getLocales')
            ->will($this->returnValue(['en']))
        ;
        $this->localeProvider
            ->method('getDefaultLocale')
            ->will($this->returnValue('en'))
        ;
        $this->localeProvider
            ->method('getRequiredLocales')
            ->will($this->returnValue(['en']))
        ;

        $this->translationForm = $this->getMockBuilder(TranslationForm::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getFieldsOptions',
                ]
            )
            ->getMock()
        ;
        $this->translationForm
            ->method('getFieldsOptions')
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

        $this->translationsListener = new TranslationsListener($this->translationForm);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $translationsType = new TranslationsType(
            $this->translationsListener,
            $this->localeProvider
        );


        return [
            new PreloadedExtension(
                [
                    $translationsType,
                ],
                []
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
            ->setName('foo')
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
