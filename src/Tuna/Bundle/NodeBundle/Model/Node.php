<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use TunaCMS\Bundle\NodeBundle\Traits\NodeTrait;
use TunaCMS\CommonComponent\Traits\IdTrait;

/**
 * AbstractNode
 */
abstract class Node implements NodeInterface
{
    use ORMBehaviors\Sluggable\Sluggable;
    use IdTrait;
    use NodeTrait;

    public function __construct()
    {
        $this->nodeTraitConstructor();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->translate()->getLabel();
    }

    /**
     * {@inheritdoc}
     *
     * @return Node
     */
    public function setLabel($label = null)
    {
        $this->translate()->setLabel($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->translate()->getUrl();
    }

    /**
     * {@inheritdoc}
     *
     * @return Node
     */
    public function setUrl($url = null)
    {
        $this->translate()->setUrl($url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->translate()->getName();
    }

    /**
     * {@inheritdoc}
     *
     * @return Node
     */
    public function setName($name)
    {
        $this->translate()->setName($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->translate()->isPublished();
    }

    /**
     * {@inheritdoc}
     *
     * @return Node
     */
    public function setPublished($published)
    {
        $this->translate()->setPublished($published);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSluggableFields()
    {
        return ['name'];
    }
}
