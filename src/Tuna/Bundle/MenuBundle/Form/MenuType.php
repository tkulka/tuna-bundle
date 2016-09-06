<?php

namespace TheCodeine\MenuBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\PageBundle\Entity\Page;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pageId = $builder->getData()->getId();

        $builder
            ->add('clickable')
            ->add('published', CheckboxType::class)
            ->add('parent', null,
                array(
                    'query_builder' => function (
                        EntityRepository $er) use (
                        $pageId
                    ) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.root', 'ASC')
                            ->addOrderBy('p.lft', 'ASC')
                            ->where("p.id != '$pageId'");
                    },
                    'property' => 'indentedName',
                    'placeholder' => '-',
                )
            )
            ->add('page', EntityType::class, array(
                'class' => Page::class,
                'property' => 'title',
                'empty_value' => 'No Page',
                'attr' => array(
                    'class' => 'filtered',
                )
            ))
            ->add('translations', GedmoTranslationsType::class, array(
                'translatable_class' => Menu::class,
                'fields' => array(
                    'label' => array(),
                    'path' => array(),
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Menu::class,
        ));
    }
}
