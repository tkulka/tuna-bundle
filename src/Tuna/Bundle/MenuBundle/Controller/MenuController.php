<?php

namespace TheCodeine\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\Form\MenuType;

class MenuController extends Controller
{
    protected function getRedirect()
    {
        return $this->redirectToRoute('thecodeine_menu_menu_list');
    }

    /**
     * @Route("")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('TheCodeineMenuBundle:Menu');
        $nodes = $repository->getNodesHierarchy();
        $tree = $repository->buildTree($nodes);

        return array('tree' => $tree);
    }

    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            dump($menu);
            $em->persist($menu);
            $em->flush();

            return $this->getRedirect();
        }

        return array(
            'form' => $form->createView(),
            'menu' => $menu,
        );
    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     */
    public function editAction(Request $request, Menu $menu)
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->getRedirect();
        }

        return array(
            'form' => $form->createView(),
            'menu' => $menu,
        );
    }
}
