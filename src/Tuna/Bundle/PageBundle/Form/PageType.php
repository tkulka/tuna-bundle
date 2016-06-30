<?php

namespace TheCodeine\PageBundle\Form;

class PageType extends BasePageType
{
    protected function getTranslatableClass()
    {
        return 'TheCodeine\PageBundle\Entity\Page';
    }

}
