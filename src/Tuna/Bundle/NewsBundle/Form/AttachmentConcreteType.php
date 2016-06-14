<?php

namespace TheCodeine\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TheCodeine\NewsBundle\Form\Type\AttachmentNameType;

class AttachmentConcreteType extends AbstractType
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
            ->add('title', 'text', array(
                'attr' => array(
                    'class' => 'to-hide'
                ),
            ))
            ->add('file', 'file', array(
                'attr' => array(
                    'class' => 'to-hide'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TheCodeine\NewsBundle\Entity\Attachment',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'thecodeine_newsbundle_attachment_concrete';
    }

}

