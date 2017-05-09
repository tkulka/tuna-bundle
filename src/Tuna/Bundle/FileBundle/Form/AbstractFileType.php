<?php

namespace TheCodeine\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFileType extends AbstractType
{
    /**
     * @return string Fully qualified class name of
     */
    abstract protected function getEntityClass();

    /**
     * Override this method instead of providing `dropzone_options` in `configureOptions`
     * if these options should be merged (in child class or in form builder)
     */
    public static function getDropzoneDefaultOptions()
    {
        return [];
    }

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
        $view->vars['button_label'] = $options['button_label'];
        $view->vars['show_filename'] = $options['show_filename'];
        $view->vars['init_dropzone'] = $options['init_dropzone'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultDropzoneOptions = static::getDropzoneDefaultOptions() + [
                'maxFilesize' => self::getPHPMaxFilesize(),
                'acceptedFiles' => '',
            ];

        $resolver->setDefaults([
            'translation_domain' => 'tuna_admin',
            'data_class' => $this->getEntityClass(),
            'error_bubbling' => false,
            'dropzone_options' => $defaultDropzoneOptions,
            'button_label' => 'ui.form.labels.file.button',
            'show_filename' => true,
            'init_dropzone' => true,
            'attr' => [
                'deletable' => true,
            ],
        ]);

        $resolver->setNormalizer('dropzone_options', function (Options $options, $value) use ($defaultDropzoneOptions) {
            if (array_key_exists('maxFilesize', $value)) {
                $value['maxFilesize'] = min(self::getPHPMaxFilesize(), $value['maxFilesize']);
            }

            return array_merge($defaultDropzoneOptions, $value);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_file';
    }

    /**
     * @return int upload_max_filesize in MB
     */
    public static function getPHPMaxFilesize()
    {
        return self::getFilesize(ini_get('upload_max_filesize'));
    }

    /**
     * Converts filesize string (compatible with php.ini values) to MB
     *
     * @param $filesize in bytes, or with unit (`2043`, `273K`, `1.2M`, `0.3G`)
     * @return float
     */
    protected static function getFilesize($filesize)
    {
        $value = (float)($filesize);
        $unit = substr($filesize, -1);

        switch ($unit) {
            case 'K':
                return $value / 1024;
            case 'G':
                return $value * 1024;
            case 'M':
            default:
                return $value;
        }
    }
}
