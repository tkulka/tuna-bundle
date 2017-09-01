<?php

namespace TunaCMS\Bundle\CategoryBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\CategoryBundle\Entity\Category;

class CategoryType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct($dataClass = Category::class)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => $this->dataClass,
                'fields' => [
                    'name' => [
                        'required' => true,
                        'label' => 'ui.form.labels.name',
                        'attr' => [
                            'placeholder' => 'ui.form.labels.name'
                        ]
                    ],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'error_bubbling' => false,
            'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_category';
    }
}
