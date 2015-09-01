<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

use TheCodeine\NewsBundle\Entity\Category,
    TheCodeine\PageBundle\Entity\Page,
    TheCodeine\PageBundle\Form\PageType;

class PageController extends Controller
{

    /**
     * @Route("/admin/page/{id}/delete", requirements={"id" = "\d+"}, name="thecodeine_admin_page_delete")
     * @Template()
     */
    public function deleteAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/admin/page/{id}/edit", name="thecodeine_admin_page_edit")
     * @Template()
     */
    public function editAction(Page $page = null)
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        if (null == $page) {
            $page = new Page();
            if($request->get('cid')) {
                $category = $em->find('TheCodeine\NewsBundle\Entity\Category', $request->get('cid'));
                $page->setCategory($category);
            }
        }

        $form = $this->createForm(new PageType(), $page);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl('thecodeine_admin_page_edit', array('id' => $page->getId())));
        }

        $request->query->add(array(
            'cid' => $page->getCategory()->getId()
        ));

        return array(
            'page'      => $page,
            'form'      => $form->createView(),
            'category'  => $page->getCategory()
        );
    }
}