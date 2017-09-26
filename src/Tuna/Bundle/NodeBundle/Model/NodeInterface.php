<?php

namespace TunaCMS\Bundle\NodeBundle\Model;

use TunaCMS\Component\Common\Model\IdInterface;

interface NodeInterface extends IdInterface
{
    /**
     * @return MenuNodeInterface
     */
    public function getMenu();

    /**
     * @param MenuNodeInterface $menu
     *
     * @return $this
     */
    public function setMenu(MenuNodeInterface $menu = null);

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
     * @return MetadataInterface|null
     */
    public function getMetadata();

    /**
     * @param MetadataInterface|null $metadata
     *
     * @return $this
     */
    public function setMetadata(MetadataInterface $metadata = null);

    /**
     * @return string
     */
    public function getSlug();
}
