<?php

namespace TunaCMS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("", name="tuna_cms_dashboard")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('tunacms_admin_menu_index');
    }
}
