<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TheCodeine\NewsBundle\Form\Type\AttachmentNameType;

class AttachmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', 'hidden')
            ->add('file', 'file', array())
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'TheCodeine\NewsBundle\Entity\Attachment',
                'fields' => array(
                    'title' => array(
                        'required' => true,
                        'attr' => array(
                            'placeholder' => 'Attachment title'
                        )
                    )
                )
            ))
            ->add('save', 'submit');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\NewsBundle\Entity\Attachment',
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

