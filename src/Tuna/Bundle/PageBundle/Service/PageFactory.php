<?php

namespace TheCodeine\PageBundle\Service;

use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\CategoryPage;
use TheCodeine\PageBundle\Form\CategoryPageType;
use TheCodeine\PageBundle\Form\PageType;

class PageFactory
{
    /**
     * @var string
     */
    protected $model;

    /**
     * PageFactory constructor.
     *
     * @param string $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return new $this->model();
    }

    /**
     * @param AbstractPage|null $abstractPage
     *
     * @return CategoryPageType|PageType
     */
    public function getFormInstance(AbstractPage $abstractPage = null)
    {
        switch (true) {
            case $abstractPage instanceof CategoryPage:
                return CategoryPageType::class;
            default:
                return PageType::class;
        }
    }
}