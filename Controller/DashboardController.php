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
     * @Route("/", name="tunacms_dashboard")
     */
    public function indexAction(Request $request)
    {
        $menuModel = $this->get('tuna_cms_bundle_menu.factory.menu_factory')->getModel();
        /* @var \TunaCMS\Bundle\MenuBundle\Repository\MenuRepositoryInterface $repository */
        $repository = $this->getDoctrine()->getRepository($menuModel);
        $roots = $repository->getRoots();

        foreach ($roots as $root) {
            $repository->loadWholeTree($root);
        }

        return $this->render('@TunaCMSAdmin/menu/index.html.twig', [
            'menu_items' => $roots,
        ]);
    }
}
