<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Form;

use A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener;
use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsFieldsType;
use A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use TunaCMS\Bundle\FileBundle\Entity\Attachment;
use TunaCMS\Bundle\FileBundle\Entity\AttachmentTranslation;
use TunaCMS\Bundle\FileBundle\Entity\File;
use TunaCMS\Bundle\FileBundle\Form\AttachmentType;

class AttachmentTypeTest extends TypeTestCase
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
            ->getMock();

        $this->translationForm
            ->method('getGedmoTranslatableListener')
            ->will($this->returnValue($this->translatableListener));

        $this->translationForm
            ->method('getTranslatableFields')
            ->will($this->returnValue([]));

        $this->translationForm
            ->method('getChildrenOptions')
            ->will(
                $this->returnValue(
                    [
                        'en' => [
                            'title' => [
                                'required' => true,
                                'field_type' => TextType::class,
                            ],
                        ],
                    ]
                )
            );

        $this->translationForm
            ->method('getTranslationClass')
            ->will($this->returnValue(AttachmentTranslation::class));

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
            'position' => 2,
            'file' => [
                'path' => '/root/foo',
                'filename' => 'test.bar',
            ],
            'translations' => [
                'en' => [
                    'title' => 'foo',
                ],
            ],
        ];

        $file = new File();
        $file
            ->setPath($formData['file']['path'])
            ->setFilename($formData['file']['filename']);
        $translation = new AttachmentTranslation();
        $translation
            ->setLocale('en')
            ->setField('title')
            ->setContent('foo');
        $object = new Attachment();
        $object
            ->setPosition($formData['position'])
            ->setFile($file)
            ->addTranslation($translation);

        $form = $this->factory->create(AttachmentType::class);

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
