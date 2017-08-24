<?php

namespace TunaCMS\Bundle\NodeBundle\Sluggable;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use TunaCMS\Bundle\NodeBundle\Entity\NodeTranslation;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeTranslationManager
{
    const FIELD_SLUG_SOURCE = 'name';
    const FIELD_SLUG = 'slug';
    const FIELD_CONTENT = 'content';
    const FIELD_FIELD = 'field';
    const FIELD_LOCALE = 'locale';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param NodeInterface $node
     * @param $locale
     *
     * @return NodeInterface
     */
    public function getTranslatedEntity(NodeInterface $node, $locale)
    {
        $node->setTranslatableLocale($locale);
        $this->em->refresh($node);

        return $node;
    }

    /**
     * @param $text
     * @param string $separator
     *
     * @return mixed
     */
    public static function urlize($text, $separator = '-')
    {
        return Urlizer::transliterate($text, $separator);
    }

    /**
     * @param NodeTranslation $slugSourceTranslation
     *
     * @return \Doctrine\ORM\Query
     */
    public function getChildrenUpdateQuery(NodeTranslation $slugSourceTranslation)
    {
        $qb = $this->em->createQueryBuilder();
        $contentField = 'nt.'.self::FIELD_CONTENT;
        $fieldField = 'nt.'.self::FIELD_FIELD;
        $localeField = 'nt.'.self::FIELD_LOCALE;

        $node = $this->getTranslatedEntity($slugSourceTranslation->getObject(), $slugSourceTranslation->getLocale());

        $oldSlug = $node->getSlug();
        $newSlug = $this->generateSlug($slugSourceTranslation);

        $qb
            ->update(NodeTranslation::class, 'nt')
            ->set($contentField, $qb->expr()->concat(
                $qb->expr()->literal($newSlug),
                $qb->expr()->substring($contentField, mb_strlen($oldSlug) + 1)
            ))
            ->where($qb->expr()->like(
                $contentField,
                $qb->expr()->literal($oldSlug.'%'))
            )
            ->andWhere($qb->expr()->eq(
                $fieldField,
                $qb->expr()->literal(self::FIELD_SLUG)
            ))
            ->andWhere($qb->expr()->eq(
                $localeField,
                $qb->expr()->literal($slugSourceTranslation->getLocale())
            ));

        return $qb->getQuery();
    }

    /**
     * @param NodeTranslation $translation
     *
     * @return string
     */
    public function generateSlug(NodeTranslation $translation)
    {
        $parent = $this->getTranslatedEntity($translation->getObject()->getParent(), $translation->getLocale());

        return sprintf('%s%s%s',
            $parent->getSlug(),
            $parent->getSlug() ? '/' : '',
            $this->urlize($translation->getContent())
        );
    }

    /**
     * @param NodeTranslation $slugSourceTranslation
     *
     * @return NodeTranslation
     */
    public function generateSlugTranslation(NodeTranslation $slugSourceTranslation, $ignoreExistingTranslations = false)
    {
        $slug = $this->generateSlug($slugSourceTranslation);

        if (!$ignoreExistingTranslations) {
            foreach ($slugSourceTranslation->getObject()->getTranslations() as $translation) {
                if ($translation->getLocale() !== $slugSourceTranslation->getLocale() || $translation->getField() !== self::FIELD_SLUG) {
                    continue;
                }

                $translation->setContent($slug);

                return $translation;
            }
        }

        $translation = new NodeTranslation(self::FIELD_SLUG, $slugSourceTranslation->getLocale(), $slug);
        $translation->setObject($slugSourceTranslation->getObject());

        return $translation;
    }

    public function regenerateSlugTranslations(NodeInterface $root = null, $locale = null)
    {
        $slugSourceTranslations = $this->getFieldTranslations(self::FIELD_SLUG_SOURCE, $root, $locale);

        foreach ($slugSourceTranslations as $slugSourceTranslation) {
            $this->em->persist($this->generateSlugTranslation($slugSourceTranslation));
            $this->em->flush();
        }
    }

    /**
     * @param $field
     * @param NodeInterface|null $root
     * @param null $locale
     *
     * @return NodeTranslation[]
     */
    private function getFieldTranslations($field, NodeInterface $root = null, $locale = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('nt')
            ->from('TunaCMSNodeBundle:NodeTranslation', 'nt')
            ->join('nt.object', 'm')
            ->where('nt.field = :field')
            ->setParameter('field', $field)
            ->orderBy('m.lvl'); // generate slugs in proper order

        if ($root) {
            $qb
                ->andWhere('m.lft > :lft AND m.rgt < :rgt AND m.root = :root')
                ->setParameter('lft', $root->getLft())
                ->setParameter('rgt', $root->getRgt())
                ->setParameter('root', $root->getRoot());
        }

        if ($locale) {
            $qb
                ->andWhere('nt.locale = :locale')
                ->setParameter('locale', $locale);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
