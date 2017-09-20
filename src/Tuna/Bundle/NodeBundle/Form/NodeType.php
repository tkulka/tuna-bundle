<?php

namespace TunaCMS\Bundle\NodeBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeType extends AbstractType
{
    /**
     * @var string FQCN of Node entity
     */
    protected $nodeModel;

    /**
     * @var string FQCN of Metadata entity
     */
    protected $metadataModel;

    public function __construct($nodeModel, $metadataModel)
    {
        $this->metadataModel = $metadataModel;
        $this->nodeModel = $nodeModel;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $node = $builder->getData();
        $builder
            ->add('clickable', CheckboxType::class, [
                'label' => 'Clickable',
            ])
            ->add('displayingChildren', CheckboxType::class, [
                'label' => 'Display children',
            ]);

        if ($node->getLinkType() !== MenuInterface::LINK_URL) {
            $builder->add('metadata', MetadataType::class, [
                'data_class' => $this->metadataModel,
            ]);
        }

        $builder
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => $this->nodeModel,
                'fields' => $this->getTranslatableFieldsConfig($builder->getData()),
                'translation_domain' => 'tuna_admin',
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            return $this->submitEventListener($event);
        });
    }

    protected function submitEventListener(FormEvent $event)
    {
        $node = $event->getData();
        if (!$node instanceof NodeInterface) {
            return;
        }

        if (!$node->getLabel()) {
            $node->setLabel($node->getName());
        }

        $event->setData($node);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NodeInterface::class,
            'translation_domain' => 'tuna_admin',
        ]);
    }

    public static function getTranslatableFieldsConfig(NodeInterface $node)
    {
        $config = [
            'name' => [],
            'label' => [],
            'published' => [
                'label' => 'ui.form.labels.published',
            ],
            'slug' => ['display' => false],
        ];

        if (!$node->isUrlLinkType()) {
            $config['url'] = ['display' => false];
        }

        return $config;
    }
}
