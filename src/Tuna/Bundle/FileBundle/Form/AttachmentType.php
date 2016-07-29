<?php

namespace TheCodeine\FileBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\FileBundle\Entity\Attachment;

class AttachmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', Type\HiddenType::class)
            ->add('file', FileType::class)
            ->add('translations', GedmoTranslationsType::class, array(
                'translatable_class' => 'TheCodeine\FileBundle\Entity\Attachment',
                'fields' => array(
                    'title' => array(
                        'required' => true,
                        'attr' => array(
                            'placeholder' => 'Attachment title'
                        )
                    )
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Attachment::class,
            'error_bubbling' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tuna_attachment';
    }

}
