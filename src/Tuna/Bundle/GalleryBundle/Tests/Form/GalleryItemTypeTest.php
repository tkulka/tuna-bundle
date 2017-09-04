<?php

namespace TunaCMS\Bundle\GalleryBundle\Tests\Form;

use A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener;
use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsFieldsType;
use A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use TunaCMS\Bundle\FileBundle\Entity\Image;
use TunaCMS\Bundle\GalleryBundle\Entity\GalleryItem;
use TunaCMS\Bundle\GalleryBundle\Entity\GalleryItemTranslation;
use TunaCMS\Bundle\GalleryBundle\Form\GalleryItemType;
use TunaCMS\Bundle\VideoBundle\Doctrine\VideoManager;
use TunaCMS\Bundle\VideoBundle\Entity\Video;
use TunaCMS\Bundle\VideoBundle\Form\VideoUrlType;

class GalleryItemTypeTest extends TypeTestCase
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

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

    /**
     * @var VideoManager
     */
    private $videoManager;

    protected function setUp()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();

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
                            'name' => [
                                'required' => true,
                                'field_type' => TextType::class,
                            ],
                        ],
                    ]
                )
            );

        $this->translationForm
            ->method('getTranslationClass')
            ->will($this->returnValue(GalleryItemTranslation::class));

        $this->videoManager = $this->getMockBuilder(VideoManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByVideoId'])
            ->getMock();

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

        $videoUrlType = new VideoUrlType($this->videoManager);

        return [
            new PreloadedExtension(
                [
                    $translationsType,
                    $translationsFieldsType,
                    $videoUrlType,
                ], []
            ),
        ];
    }

    public function testSubmitWhenTypeIsEmpty()
    {
        $formData = [
            'type' => '',
            'position' => '2',
            'translations' => [
                'en' => [
                    'name' => 'foo',
                ],
            ],
        ];

        $object = new GalleryItem();
        $galleryItem = new GalleryItem();

        $form = $this->factory->create(GalleryItemType::class, $galleryItem);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $galleryItem);
    }

    /**
     * @dataProvider getValidTestData
     *
     * @param string $type
     * @param array|string $mediaFormData
     * @param array $mediaData
     */
    public function testSubmitValidData($type, $mediaFormData, array $mediaData)
    {
        $formData = [
            'type' => $type,
            'position' => '2',
            'translations' => [
                'en' => [
                    'name' => 'foo',
                ],
            ],
        ];

        if ($type == GalleryItem::VIDEO_TYPE) {
            $media = new Video();
            $formData['video'] = $mediaFormData;
        } else {
            $media = new Image();
            $formData['image'] = $mediaFormData;
        }

        foreach ($mediaData as $propertyPath => $value) {
            $this->accessor->setValue($media, $propertyPath, $value);
        }

        $translation = new GalleryItemTranslation();
        $translation
            ->setLocale('en')
            ->setField('name')
            ->setContent('foo');
        $object = new GalleryItem();
        $object
            ->setType($formData['type'])
            ->setPosition($formData['position'])
            ->addTranslation($translation);

        $galleryItem = new GalleryItem();

        $form = $this->factory->create(GalleryItemType::class, $galleryItem);

        $form->submit($formData);

        if ($type == GalleryItem::VIDEO_TYPE) {
            $media->setVideoId($galleryItem->getVideo()->getVideoId());
            $object->setVideo($media);
        } else {
            $object->setImage($media);
        }

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $galleryItem);

        $view = $form->createView();
        $children = $view->children;

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidTestData()
    {
        return [
            [
                GalleryItem::VIDEO_TYPE,
                'https://www.youtube.com/watch?v=o9N1nOYfAl4',
                [
                    'url' => 'https://www.youtube.com/watch?v=o9N1nOYfAl4',
                    'type' => 'youtube',
                ],
            ],
            [
                GalleryItem::VIDEO_TYPE,
                'https://vimeo.com/9636197',
                [
                    'url' => 'https://vimeo.com/9636197',
                    'type' => 'vimeo',
                ],
            ],
            [
                GalleryItem::IMAGE_TYPE,
                [
                    'path' => '/root/foo',
                    'filename' => 'test.jpeg',
                ],
                [
                    'path' => '/root/foo',
                    'filename' => 'test.jpeg',
                ],
            ],
        ];
    }
}
