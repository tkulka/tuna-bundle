<?php

namespace TheCodeine\PageBundle\Form;

final class PageType extends AbstractPageType
{
    /**
     * @inheritdoc
     */
    protected function getEntityClass()
    {
        return 'TheCodeine\PageBundle\Entity\Page';
    }

}
