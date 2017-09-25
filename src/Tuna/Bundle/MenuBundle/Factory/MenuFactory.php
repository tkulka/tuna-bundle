<?php

namespace TunaCMS\Bundle\MenuBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TypeConfig;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

class MenuFactory
{
    /**
     * @var TypeConfig[]
     */
    protected $types;

    /**
     * @var array
     */
    protected $typesClassMap;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function registerType($type, array $config)
    {
        $typeConfig = new TypeConfig();
        $typeConfig
            ->setName($type)
            ->setModel($config['model'])
            ->setForm($config['form'])
            ->setTemplates($config['templates']);

        $this->types->set($type, $typeConfig);
        $this->typesClassMap[$typeConfig->getModel()] = $typeConfig->getName();
    }

    /**
     * @param $type
     *
     * @return MenuInterface
     */
    public function getInstance($type)
    {
        $model = $this->getModel($type);

        return new $model();
    }

    public function getModel($type)
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

    public function getTypeName($object)
    {
        $model = get_class($object);

        if (!array_key_exists($model, $this->typesClassMap)) {
            $this->throwInvalidTypeException();
        }

        return $this->typesClassMap[$model];
    }

    /**
     * @param string $type
     *
     * @return TypeConfig
     * @throws \Exception
     */
    protected function getTypeConfig($type)
    {
        if (is_object($type)) {
            $type = $this->getTypeName($type);
        }

        if (!$this->types->containsKey($type)) {
            $this->throwInvalidTypeException();
        }

        return $this->types->get($type);
    }

    /**
     * @throws \Exception
     */
    protected function throwInvalidTypeException()
    {
        throw new \Exception('No type, dang');
    }
}
