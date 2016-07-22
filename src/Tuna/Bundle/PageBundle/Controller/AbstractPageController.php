<?php

namespace TheCodeine\PageBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Entity\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractPageController extends Controller
{
    /**
     * @return AbstractPage
     */
    abstract public function getNewPage();

    /**
     * @return AbstractType
     */
    abstract public function getNewFormType(AbstractPage $page = null, $validate = true);

    /**
     * @return string
     */
    abstract public function getRedirectUrl(AbstractPage $page = null);

    /**
     * @return EntityRepository
     */
    abstract public function getRepository();

    /**
     * @Route("/list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return array(
            'entities' => $this->getRepository()->findAll(),
        );
    }

    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $page = $this->getNewPage();
        $form = $this->createForm($this->getNewFormType($page, !$request->isXmlHttpRequest()), $page);

        return $this->handleCreateForm($request, $form, $page);
    }

    /**
     * @Route("/{id}/edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $page = $this->getRepository()->find($id);
        $form = $this->createForm($this->getNewFormType($page, !$request->isXmlHttpRequest()), $page);

        return $this->handleEditForm($request, $page, $form);
    }

    /**
     * @Route("/{id}/delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $page = $this->getRepository()->find($id);

        return $this->handleDeletion($page);
    }

    protected function handleDeletion($page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        return $this->redirect($this->getRedirectUrl($page));
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

                return $this->redirect($this->getRedirectUrl($page));
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
            if (!$request->isXmlHttpRequest()) {
                $em->persist($page);
                $em->flush();

                return $this->redirect($this->getRedirectUrl($page));
            }
        }

        return array(
            'page' => $page,
            'form' => $form->createView(),
        );
    }
}
