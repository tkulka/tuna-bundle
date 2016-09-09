<?php

namespace TheCodeine\MenuBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TheCodeine\MenuBundle\Entity\Menu;
use TheCodeine\MenuBundle\EventListener\PageSubscriber;
use TheCodeine\MenuBundle\Form\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AdminController extends Controller
{
    protected function getRedirect(Menu $menu)
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

        return array('menus' => $tree);
    }

    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $menu = new Menu();
        $em = $this->getDoctrine()->getManager();

        if (($parentId = $request->query->get('parentId'))) {
            $menu->setParent($em->getReference('TheCodeineMenuBundle:Menu', $parentId));
        }
        if (($pageId = $request->query->get('pageId'))) {
            $page = $em->find('TheCodeinePageBundle:Page', $pageId);
            $menu
                ->setPage($page)
                ->setLabel($page->getTitle());

            PageSubscriber::overrideTranslations($page, $menu);
        }

        $form = $this->createForm(MenuType::class, $menu);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($menu);
            $em->flush();

            return $this->getRedirect($menu);
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

            return $this->getRedirect($menu);
        }

        return array(
            'form' => $form->createView(),
            'menu' => $menu,
        );
    }

    /**
     * @Route("/reset", name="tuna_menu_reset")
     */
    public function resetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('TheCodeineMenuBundle:Menu')->findAll();
        foreach ($menu as $item) {
            $em->remove($item);
        }
        $em->flush();

        $root = new Menu('Menu');
        $em->persist($root);
        $em->flush();

        return $this->redirectToRoute('tuna_menu_create');
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->getRedirect($menu);
    }

    /**
     * @Route("/save-order")
     * @Method("POST")
     */
    public function saveOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $request->request->get('order', array());
        $entities = $em->getRepository('TheCodeineMenuBundle:Menu')->findAll();
        $pages = array();

        foreach ($entities as $entity) {
            $pages[$entity->getId()] = $entity;
        }

        foreach ($order as $pageTreeData) {
            if (isset($pages[$pageTreeData['id']])) {
                $pages[$pageTreeData['id']]->setTreeData(
                    $pageTreeData['left'],
                    $pageTreeData['right'],
                    $pageTreeData['depth'],
                    isset($pages[$pageTreeData['parent_id']]) ? $pages[$pageTreeData['parent_id']] : null
                );
            }
        }
        $em->flush();

        return new JsonResponse('ok');
    }
}
