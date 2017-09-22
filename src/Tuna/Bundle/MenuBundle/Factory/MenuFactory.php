<?php

namespace TunaCMS\Bundle\MenuBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TypeConfig;

class MenuFactory
{
    /**
     * @var TypeConfig[]
     */
    protected $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function registerType(TypeConfig $config)
    {
        $this->types[$config->getName()] = $config;
    }

    public function getEntityClass($type)
    {
        return $this->getTypeConfig($type)->getModel();
    }

    public function getFormClass($type)
    {
        return $this->getTypeConfig($type)->getForm();
    }

    public function getTemplate($type, $name)
    {
        return $this->getTypeConfig($type)->getTemplate($name);
    }

    /**
     * @param string $type
     *
     * @return mixed|TypeConfig
     * @throws \Exception
     */
    protected function getTypeConfig($type)
    {
        if (!array_key_exists($type, $this->types)) {
            throw new \Exception('No type, dang');
        }

        return $this->types[$type];
    }
}
