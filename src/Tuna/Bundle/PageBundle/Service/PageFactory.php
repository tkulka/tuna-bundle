<?php

namespace TheCodeine\PageBundle\Service;

class PageFactory
{
    /**
     * @var string
     */
    private $formClass;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * PageFactory constructor.
     *
     * @param string $formClass
     * @param string $modelClass
     */
    public function __construct($formClass, $modelClass)
    {
        $this->formClass = $formClass;
        $this->modelClass = $modelClass;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return new $this->modelClass();
    }

    /**
     * @return string
     */
    public function getFormInstance()
    {
        return $this->formClass;
    }
}