<?php

namespace TunaCMS\Bundle\MenuBundle\Form;

use AppBundle\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\MenuBundle\Model\MenuAliasInterface;

class MenuAliasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('targetMenu', EntityType::class, [
            'class' => Menu::class,
            'choice_label' => 'label',
            'attr' => [
                'class' => 'filtered',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuAliasInterface::class,
        ]);
    }

    public function getParent()
    {
        return MenuType::class;
    }
}
