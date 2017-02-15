<?php

namespace TheCodeine\FileBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Entity\Attachment;

class AttachmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', HiddenType::class)
            ->add('file', FileType::class)
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => Attachment::class,
                'fields' => [
                    'title' => [
                        'required' => true,
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'ui.form.labels.attachment.title'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class,
            'error_bubbling' => false,
            'translation_domain' => 'tuna_admin'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_attachment';
    }

}
