<?php

namespace TheCodeine\MenuBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\GedmoTranslationsType;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\PageBundle\Entity\AbstractPage;

class MenuType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nodeId = $builder->getData()->getId();

        $builder
            ->add('clickable')
            ->add('published', CheckboxType::class)
            ->add('page', EntityType::class, [
                'class' => AbstractPage::class,
                'property' => 'title',
                'empty_value' => 'Not linked to a Page',
                'attr' => ['class' => 'filtered']
            ])
            ->add('path')
            ->add('translations', GedmoTranslationsType::class, [
                'translatable_class' => Menu::class,
                'fields' => ['label' => []]
            ])
            ->add('parent', null, [
                'query_builder' => function (EntityRepository $er) use ($nodeId) {
                    return $this->queryParentElement($er, $nodeId);
                },
                'property' => 'indentedName',
                'required' => true,
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'translation_domain' => 'tuna_admin',
        ]);
    }

    /**
     * @param EntityRepository $er
     * @param int $nodeId
     * @return QueryBuilder
     */
    protected function queryParentElement(EntityRepository $er, $nodeId)
    {
        return $er->createQueryBuilder('p')
            ->orderBy('p.root', 'ASC')
            ->addOrderBy('p.lft', 'ASC')
            ->where('p.id != :nodeId')
            ->setParameter('nodeId', $nodeId);
    }
}