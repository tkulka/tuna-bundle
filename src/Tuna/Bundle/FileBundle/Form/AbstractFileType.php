<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFileType extends AbstractType
{
    /**
     * @return string Fully qualified class name of
     */
    abstract protected function getEntityClass();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', Type\HiddenType::class)
            ->add('filename', Type\HiddenType::class);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['accepted_files'] = $options['accepted_files'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'tuna_admin',
            'data_class' => $this->getEntityClass(),
            'error_bubbling' => false,
            'accepted_files' => '*',
            'attr' => [
                'deletable' => true,
            ],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'tuna_file';
    }
}
