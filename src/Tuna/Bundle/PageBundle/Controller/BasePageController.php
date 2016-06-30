<?php

namespace TheCodeine\PageBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use TheCodeine\PageBundle\Entity\BasePage;
use TheCodeine\PageBundle\Entity\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

abstract class BasePageController extends Controller
{
    /**
     * @return BasePage
     */
    abstract public function getNewPage();

    /**
     * @return AbstractType
     */
    abstract public function getNewFormType(BasePage $page);

    /**
     * @return string
     */
    abstract public function getRedirectUrl(BasePage $page);

    /**
     * @return EntityRepository
     */
    abstract public function getRepository();

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param BasePage $page
     *
     * @return array
     */
    public function showAction(Request $request, BasePage $page)
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
            'pagesList' => $this->getRepository()->findAll(),
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
        $page = $this->getNewPage();
        $form = $this->createForm($this->getNewFormType(), $page);

        return $this->handleCreateForm($request, $form, $page);
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param integer $id
     *
     * @return array
     */
    public function editAction(Request $request, $id)
    {
        $page = $this->getRepository()->find($id);
        $form = $this->createForm($this->getNewFormType(), $page);

        return $this->handleEditForm($request, $page, $form);
    }

    /**
     *
     * @Template()
     *
     * @param Page $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(BasePage $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        return $this->redirectToRoute($this->getRedirectUrl());
    }

    /**
     * @param Request $request
     * @param $page
     * @param $form
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleEditForm(Request $request, $page, $form)
    {
        $em = $this->getDoctrine()->getManager();
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

                return $this->redirectToRoute($this->getRedirectUrl());
            }
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param $form
     * @param $page
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleCreateForm(Request $request, $form, $page)
    {
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            if ($page->getImage()->getFile() == null) {
                $page->setImage(null);
            }
            if (!$request->isXmlHttpRequest()) {
                $em->persist($page);
                $em->flush();

                return $this->redirectToRoute($this->getRedirectUrl());
            }
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }
}
