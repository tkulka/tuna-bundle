<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\CommonComponent\Model\TranslatableInterface;

interface RouteInterface extends TreeInterface, TranslatableInterface
{
    /**
     * Name of the controller if you want to call it explicitly (e.g. `AppBundle:Page:showItem`)
     *
     * @return string|null
     */
    public function getControllerAction();

    /**
     * @param string|null $action
     *
     * @return $this
     */
    public function setControllerAction($action = null);

    /**
     * Name of the template if you want to call it explicitly (e.g. `page/show_item.html.twig`)
     *
     * @return string|null
     */
    public function getActionTemplate();

    /**
     * @param string|null $template
     *
     * @return $this
     */
    public function setActionTemplate($template = null);

    /**
     * Force field to have `null` slug
     * (important, because without this flag roots could have slugs of `''`, `'-1'`, `'-2'` to ensure uniqueness)
     *
     * @return boolean
     */
    public function isRootOfATree();

    /**
     * @param boolean $rootOfATree
     *
     * @return $this
     */
    public function setRootOfATree($rootOfATree);

    /**
     * Name of Node implementation, e.g. `Page`, `Node`, `News`, `Product`
     *
     * @return string
     */
    public function getTypeName();

    /**
     * ##########################################
     *
     *          Translatable fields:
     *
     * ##########################################
     */

    /**
     * Slug should be based on `parent.slug` and `this.name`
     *
     * @return string|null
     */
    public function getSlug();

    /**
     * @param string|null $slug
     *
     * @return $this
     */
    public function setSlug($slug = null);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @param boolean $published
     *
     * @return $this
     */
    public function setPublished($published);

    /**
     * @return MetadataInterface|null
     */
    public function getMetadata();

    /**
     * @param MetadataInterface|null $metadata
     *
     * @return $this
     */
    public function setMetadata(MetadataInterface $metadata = null);
}
