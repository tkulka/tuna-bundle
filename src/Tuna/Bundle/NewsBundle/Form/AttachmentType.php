<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->setMethod('POST')
            ->setAttribute('enctype', 'multipart/form-data')
            ->add('position', 'hidden')
            ->add('file', 'file', array())
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'TheCodeine\NewsBundle\Entity\Attachment',
                'fields' => array(
                    'title' => array(
                        'required' => true,
                        'attr' => array(
                            'placeholder' => 'Attachment name'
                        )
                    )
                )
            ))
            ->add('save', 'submit');

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'render_fieldset' => false,
            'data_class' => 'TheCodeine\NewsBundle\Entity\Attachment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_newsbundle_attachment';
    }

}

