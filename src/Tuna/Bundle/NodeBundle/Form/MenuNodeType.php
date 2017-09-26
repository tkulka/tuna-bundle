<?php

namespace TunaCMS\Bundle\NodeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\MenuBundle\Form\MenuType;
use TunaCMS\Bundle\NodeBundle\Factory\NodeFactory;
use TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface;

class MenuNodeType extends AbstractType
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var \TunaCMS\Bundle\NodeBundle\Model\MenuNodeInterface $menu */
        $menu = $builder->getData();
        $nodeForm = $this->nodeFactory->getFormClass($menu->getNode());

        $builder->add('node', $nodeForm, [
            'label' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuNodeInterface::class,
            'translation_domain' => 'tuna_admin',
            'node_type' => null,
        ]);
    }

    public function getParent()
    {
        return MenuType::class;
    }
}
