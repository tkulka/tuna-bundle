<?php

namespace TheCodeine\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("menu")
 */
class MenuController extends \TheCodeine\MenuBundle\Controller\MenuController
{
    protected function getRedirect()
    {
        return $this->redirectToRoute('thecodeine_admin_menu_list');
    }
}
