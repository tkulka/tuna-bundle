<?php

namespace TunaCMS\Bundle\MenuBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use TunaCMS\Bundle\MenuBundle\DependencyInjection\TypeConfig;
use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;

class MenuFactory
{
    const BASE_TYPE = 'menu';

    /**
     * @var TypeConfig[]
     */
    protected $types;

    /**
     * @var array
     */
    protected $typesClassMap = [];

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
    public function getInstance($type = null)
    {
        $model = $this->getModel($type);

        return new $model();
    }

    public function getModel($type = null)
    {
        return $this->getTypeConfig($type)->getModel();
    }

    public function getFormClass($type = null)
    {
        return $this->getTypeConfig($type)->getForm();
    }

    public function getTemplate($type = null, $name)
    {
        return $this->getTypeConfig($type)->getTemplate($name);
    }

    public function getTypeName($object)
    {
        $model = get_class($object);

        $typesClassMap = $this->getTypesClassMap();
        if (!array_key_exists($model, $typesClassMap)) {
            $this->throwInvalidTypeException($model);
        }

        return $typesClassMap[$model];
    }

    /**
     * @param string $type
     *
     * @return TypeConfig
     * @throws \Exception
     */
    protected function getTypeConfig($type = null)
    {
        if ($type == null) {
            $type = static::BASE_TYPE;
        }

        if (is_object($type)) {
            $type = $this->getTypeName($type);
        }

        if (!$this->types->containsKey($type)) {
            $this->throwInvalidTypeException($type);
        }

        return $this->types->get($type);
    }

    /**
     * @return array
     */
    protected function getTypesClassMap()
    {
        return $this->typesClassMap;
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     */
    protected function throwInvalidTypeException($type)
    {
        throw new \InvalidArgumentException(sprintf('Type "%s" is not registered.', $type));
    }
}
