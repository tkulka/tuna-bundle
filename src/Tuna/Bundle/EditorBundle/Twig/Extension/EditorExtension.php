<?php

namespace TheCodeine\EditorBundle\Twig\Extension;

class EditorExtension extends \Twig_Extension
{
    /**
     * @var boolean
     */
    protected $editorIncluded;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct($autoinclude, $standalone, $basePath)
    {
        $this->ckeditorIncluded = $autoinclude;
        $this->standalone = $standalone;
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'the_codeine_editor';
    }
}