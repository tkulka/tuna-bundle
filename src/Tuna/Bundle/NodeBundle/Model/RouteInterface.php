<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

interface RouteInterface extends TreeInterface
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
