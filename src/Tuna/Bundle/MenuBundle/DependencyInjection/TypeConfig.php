<?php

namespace TunaCMS\Bundle\MenuBundle\DependencyInjection;

class TypeConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string FQCN
     */
    private $model;

    /**
     * @var string FQCN
     */
    private $form;

    /**
     * @var array
     */
    private $templates;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return $this
     *
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return $this
     *
     * @param string $form
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param $name
     *
     * @return string
     * @throws \Exception
     */
    public function getTemplate($name)
    {
        if (!array_key_exists($name, $this->getTemplates())) {
            throw new \Exception('Template not found');
        }

        return $this->getTemplates()[$name];
    }

    /**
     * @return $this
     *
     * @param array $templates
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;

        return $this;
    }
}
