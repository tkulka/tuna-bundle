<?php

namespace TunaCMS\Bundle\NodeBundle;

use TunaCMS\Bundle\MenuBundle\Model\MenuInterface;
use TunaCMS\Bundle\NodeBundle\Model\NodeInterface;

class NodeManager
{
    const BASE_TYPE = 'node';

    protected $nodeTypes;

    protected $nodeTypesMap;

    public function __construct($nodeTypesConfig)
    {
        $this->nodeTypes = $nodeTypesConfig;
    }

    /**
     * @param NodeInterface|string $nodeType NodeInterface object or type of a node (as defined in tuna_cms_node.node_types config)
     *
     * @return string FQCN of model
     */
    public function getModel($nodeType = null)
    {
        return $this->getTypeConfigField($nodeType, 'model');
    }

    /**
     * @param NodeInterface|string $nodeType NodeInterface object or type of a node (as defined in tuna_cms_node.node_types config)
     *
     * @return NodeInterface
     */
    public function getNewInstance($nodeType = null)
    {
        $model = $this->getModel($nodeType);

        return new $model;
    }

    /**
     * @param NodeInterface|string $nodeType NodeInterface object or type of a node (as defined in tuna_cms_node.node_types config)
     *
     * @return string FQCN of formType
     */
    public function getFormType($nodeType = null)
    {
        return $this->getTypeConfigField($nodeType, 'form');
    }

    /**
     * @param string $template Template name (index|edit|node_item)
     * @param NodeInterface|string $nodeType NodeInterface object or type of a node (as defined in tuna_cms_node.node_types config)
     */
    public function getTemplate($template, $nodeType = null)
    {
        return $this->getTypeConfigField($nodeType, 'templates')[$template];
    }

    public function resolveType(MenuInterface $node)
    {
        $className = get_class($node);
        $map = $this->getNodesMap();

        if (array_key_exists($className, $map)) {
            return $map[$className];
        }

        return static::BASE_TYPE;
    }

    protected function getTypeConfigField($nodeType = null, $field)
    {
        if ($nodeType instanceof NodeInterface) {
            $nodeType = $this->resolveType($nodeType);
        }

        if (!array_key_exists($nodeType, $this->nodeTypes)) {
            $nodeType = static::BASE_TYPE;
        }

        return $this->nodeTypes[$nodeType][$field];
    }

    protected function getNodesMap()
    {
        if (!$this->nodeTypesMap) {
            $this->nodeTypesMap = [];
            foreach ($this->nodeTypes as $type => $config) {
                $this->nodeTypesMap[$config['model']] = $type;
            }
        }

        return $this->nodeTypesMap;
    }
}
