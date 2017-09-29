<?php

namespace TunaCMS\Bundle\NodeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
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
        $node = $this->getOrCreateNode($builder->getData(), $options);
        $nodeFormType = $this->nodeFactory->getFormClass($node);

        $builder->add('node', $nodeFormType, [
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

    protected function getOrCreateNode(MenuNodeInterface $menu, array $options)
    {
        if ($menu->getNode()) {
            return $menu->getNode();
        }

        if (!isset($options['node_type'])) {
            throw new InvalidArgumentException('You have to either provide $menu with existing `node` or `node_type` option.');
        }

        $node = $this->nodeFactory->getInstance($options['node_type']);
        $menu->setNode($node);

        return $node;
    }
}
