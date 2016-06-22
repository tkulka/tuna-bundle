<?php

namespace TheCodeine\PageBundle\Controller;

use TheCodeine\PageBundle\Entity\Page;
use TheCodeine\PageBundle\Form\PageType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param Page $page
     *
     * @return array
     */
    public function showAction(Request $request, Page $page)
    {
        return array(
            'page' => $page
        );
    }

    /**
     *
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        return array(
            'pagesList' => $this->getDoctrine()->getManager()->getRepository("TheCodeinePageBundle:Page")->findAll(),
        );
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {

        $page = new Page();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PageType(), $page);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($page->getImage()->getFile() == null) {
                $page->setImage(null);
            }
            if (!$request->isXmlHttpRequest()) {
                $em->persist($page);
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_page_edit', array('id' => $page->getId())));
            }
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param Page $page
     *
     * @return array
     */
    public function editAction(Request $request, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        if (null == $page) {
            $page = new Page();
        }

        $form = $this->createForm(new PageType(), $page);

        $originalAttachments = new ArrayCollection();
        foreach ($page->getAttachments() as $attachment) {
            $originalAttachments[] = $attachment;
        }

        $originalGalleryItems = new ArrayCollection();
        if ($page->getGallery()) {
            foreach ($page->getGallery()->getItems() as $item) {
                $originalGalleryItems[] = $item;
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('image')->get('remove')->getData() == '1') {
                $em->remove($page->getImage());
                $page->setImage(null);
            }

            foreach ($originalAttachments as $attachment) {
                if (false === $page->getAttachments()->contains($attachment)) {
                    $em->remove($attachment);
                }
            }

            foreach ($originalGalleryItems as $item) {
                if (false === $page->getGallery()->getItems()->contains($item)) {
                    $em->remove($item);
                }
            }

            if (!$request->isXmlHttpRequest()) {
                $em->persist($page);
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_page_edit', array('id' => $page->getId())));
            }
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @Template()
     *
     * @param Page $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        return $this->redirect($this->generateUrl('tuna_page_list'));
    }
}
