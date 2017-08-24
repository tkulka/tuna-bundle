<?php

namespace TunaCMS\AdminBundle\Form\DataMapper;

use A2lix\TranslationFormBundle\Form\DataMapper\GedmoTranslationMapper;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use TunaCMS\AdminBundle\Doctrine\ClassMetadataReader;

class TunaTranslationMapper extends GedmoTranslationMapper
{
    /**
     * @var ClassMetadataReader
     */
    protected $classMetadataReader;

    /**
     * @param ClassMetadataReader $classMetadataReader
     */
    public function __construct(ClassMetadataReader $classMetadataReader = null)
    {
        $this->classMetadataReader = $classMetadataReader;
    }

    public function mapDataToForms($data, $forms)
    {
        if (null === $data || [] === $data) {
            return;
        }

        if (!is_array($data) && !is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        foreach ($forms as $translationsFieldsForm) {
            $locale = $translationsFieldsForm->getConfig()->getName();

            $tmpFormData = [];
            foreach ($data as $translation) {
                if ($locale === $translation->getLocale()) {
                    $value = $translation->getContent();

                    if ($this->classMetadataReader->getPropertyType($translation->getObject(), $translation->getField()) === Type::BOOLEAN) {
                        $value = (boolean)$value;
                    }
                    $tmpFormData[$translation->getField()] = $value;
                }
            }
            $translationsFieldsForm->setData($tmpFormData);
        }
    }

    public function mapFormsToData($forms, &$data)
    {
        parent::mapFormsToData($forms, $data);

        /* @var $translation \Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation */
        /* @var $data \Doctrine\Common\Collections\ArrayCollection */
        foreach ($data as $translation) {
            // map boolean values to '1' or '0'
            if ($this->classMetadataReader->getPropertyType($translation->getObject(), $translation->getField()) === Type::BOOLEAN) {
                $translation->setContent($translation->getContent() ? '1' : '0');
            }
        }
    }
}
