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

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'include_the_codeine_editor' => new \Twig_Function_Method($this, 'includeEditor', array('is_safe' => array('html'))),
        );
    }

    public function includeEditor()
    {
        if (!$this->environment->hasExtension('asset')) {
            return;
        }

        if (!$this->editorIncluded) {
            $this->editorIncluded = true;
        }

        if (!$this->ckeditorIncluded) {

            $asset = $this->environment->getExtension('asset');

            $js = array();
            if ($this->standalone) {
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/jquery/dist/jquery.min.js');
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/bootstrap/dist/js/bootstrap.min.js');
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/underscore/underscore-min.js');
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/components-backbone/backbone-min.js');
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/summernote/dist/summernote.js');
                $js[] = $asset->getAssetUrl($this->basePath . '/vendor/jquery-htmlclean/jquery.htmlClean.js');
            }
            $js[] = $asset->getAssetUrl($this->basePath . '/js/editor.js');

            $css = array();
            if ($this->standalone) {
                $css[] = $asset->getAssetUrl($this->basePath . '/vendor/bootstrap/dist/css/bootstrap.min.css');
                $css[] = $asset->getAssetUrl($this->basePath . '/vendor/font-awesome/css/font-awesome.min.css');
                $css[] = $asset->getAssetUrl($this->basePath . '/vendor/summernote/dist/summernote.css');
            }

            foreach ($js as $url) {
                echo sprintf('<script type="text/javascript" src="%s" type="text/javascript" charset="utf-8"></script>', $url);
            }

            foreach ($css as $url) {
                echo sprintf('<link rel="stylesheet" href="%s">', $url);
            }
            $this->ckeditorIncluded = true;
        }
    }
}