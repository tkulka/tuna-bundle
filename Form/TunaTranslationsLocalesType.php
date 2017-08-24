<?php

namespace TunaCMS\AdminBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsLocalesType;
use Symfony\Component\Form\FormBuilderInterface;
use TunaCMS\AdminBundle\Doctrine\ClassMetadataReader;
use TunaCMS\AdminBundle\Form\DataMapper\TunaTranslationMapper;

/**
 * this class fixes translations of boolean values
 */
class TunaTranslationsLocalesType extends GedmoTranslationsLocalesType
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isDefaultTranslation = ('defaultLocale' === $builder->getName());

        // Custom mapper for translations
        if (!$isDefaultTranslation) {
            $builder->setDataMapper(new TunaTranslationMapper($this->classMetadataReader));
        }

        foreach ($options['locales'] as $locale) {
            if (isset($options['fields_options'][$locale])) {
                $builder->add($locale, 'a2lix_translationsFields', [
                    'fields' => $options['fields_options'][$locale],
                    'translation_class' => $options['translation_class'],
                    'inherit_data' => $isDefaultTranslation,
                ]);
            }
        }
    }
}
