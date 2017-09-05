<?php

namespace TunaCMS\Bundle\FileBundle\Tests\Form;

use A2lix\TranslationFormBundle\Form\EventListener\TranslationsListener;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use A2lix\TranslationFormBundle\Locale\LocaleProviderInterface;
use A2lix\TranslationFormBundle\TranslationForm\TranslationForm;
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
                            'title' => [
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
            'position' => '2',
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
            ->setFilename($formData['file']['filename'])
        ;
        $translation = new AttachmentTranslation();
        $translation
            ->setLocale('en')
            ->setTitle('foo')
        ;
        $object = new Attachment();
        $object
            ->addTranslation($translation)
            ->setPosition($formData['position'])
            ->setFile($file)
        ;

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
