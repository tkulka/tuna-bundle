<?php

namespace TunaCMS\Bundle\FileBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\FileBundle\Entity\Image;

class ImageType extends AbstractFileType
{
    const MAX_FILESIZE = 10;
    const ACCEPTED_FILES = '.png,.jpg,.gif,.jpeg';

    public static function getDropzoneDefaultOptions()
    {
        return array_merge(
            parent::getDropzoneDefaultOptions(),
            [
                'clickable' => '.btn-main-image',
                'maxFiles' => 1,
                'maxFilesize' => self::MAX_FILESIZE,
                'acceptedFiles' => self::ACCEPTED_FILES,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass()
    {
        return Image::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if ($options['scale_preview_thumbnail'] !== null) {
            @trigger_error(get_class($this) . ': The `scale_preview_thumbnail` option is deprecated. Use `image_filter` option instead (set it to `false` to disable use of filter).', E_USER_DEPRECATED);
        }
        $view->vars['scale_preview_thumbnail'] = $options['scale_preview_thumbnail'];
        $view->vars['image_filter'] = $options['image_filter'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'ui.form.labels.image.default',
            'scale_preview_thumbnail' => null,
            'image_filter' => 'tuna_admin_thumb',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tuna_image';
    }
}
