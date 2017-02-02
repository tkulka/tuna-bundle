<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFileType extends AbstractType
{
    /**
     * @return string Fully qualified class name of
     */
    abstract protected function getEntityClass();

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', HiddenType::class)
            ->add('filename', HiddenType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['dropzone_options'] = $options['dropzone_options'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'tuna_admin',
            'data_class' => $this->getEntityClass(),
            'error_bubbling' => false,
            'dropzone_options' => [
                'acceptedFiles' => '*',
            ],
            'attr' => [
                'deletable' => true,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_file';
    }
}
