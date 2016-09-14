<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("", name="tuna_admin_dashboard")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'menus' => $this->getDoctrine()->getRepository('TheCodeineMenuBundle:Menu')->getMenuTree(null, false),
        );
    }
}
