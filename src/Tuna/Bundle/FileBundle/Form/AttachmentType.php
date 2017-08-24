<?php

namespace TunaCMS\Bundle\FileBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\FileBundle\Entity\Attachment;

class AttachmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', HiddenType::class)
            ->add('file', FileType::class, [
                'button_label' => false,
                'show_filename' => false,
                'init_dropzone' => false,
                'attr' => [
                    'deletable' => false,
                ]
            ])
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
