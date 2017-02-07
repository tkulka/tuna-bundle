<?php

namespace TheCodeine\PageBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TheCodeine\PageBundle\Entity\AbstractPage;
use TheCodeine\PageBundle\Form\AbstractPageType;

abstract class AbstractPageController extends Controller
{
    /**
     * @return AbstractPage
     */
    abstract public function getNewPage();

    /**
     * @param AbstractPage $abstractPage
     *
     * @return AbstractPageType
     */
    abstract public function getFormType(AbstractPage $abstractPage);

    /**
     * @param Request $request
     *
     * @return string
     */
    abstract public function getRedirectUrl(Request $request);

    /**
     * @return EntityRepository
     */
    abstract public function getRepository();

    /**
     * @Route("/list", name="tuna_page_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'entities' => $this->getRepository()->findAll(),
        ];
    }

    /**
     * @Route("/create", name="tuna_page_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $abstractPage = $this->getNewPage();
        $form = $this->createForm($this->getFormType($abstractPage), $abstractPage);

        // TODO: Move this to twig
        $form->add('save', SubmitType::class);

        return $this->handleCreate($request, $form, $abstractPage);
    }

    /**
     * @Route("/{id}/edit", name="tuna_page_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, AbstractPage $abstractPage)
    {
        $originalAttachments = new ArrayCollection();
        foreach ($abstractPage->getAttachments() as $attachment) {
            $originalAttachments[] = $attachment;
        }

        $originalGalleryItems = new ArrayCollection();
        if ($abstractPage->getGallery()) {
            foreach ($abstractPage->getGallery()->getItems() as $item) {
                $originalGalleryItems[] = $item;
            }
        }

        $form = $this->createForm($this->getFormType($abstractPage), $abstractPage);

        // TODO: Move this to twig
        $form->add('save', SubmitType::class);

        return $this->handleEdit($request, $form, $abstractPage, $originalAttachments, $originalGalleryItems);
    }

    /**
     * @Route("/{id}/delete", name="tuna_page_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, AbstractPage $abstractPage)
    {
        return $this->handleDelete($request, $abstractPage);
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param AbstractPage $abstractPage
     *
     * @return array|RedirectResponse
     */
    protected function handleCreate(Request $request, Form $form, AbstractPage $abstractPage)
    {
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abstractPage);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($request));
        }

        return [
            'page' => $abstractPage,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param AbstractPage $abstractPage
     * @param ArrayCollection $originalAttachments
     * @param ArrayCollection $originalGalleryItems
     *
     * @return array|RedirectResponse
     */
    protected function handleEdit(Request $request, Form $form, AbstractPage $abstractPage, ArrayCollection $originalAttachments, ArrayCollection $originalGalleryItems)
    {
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalAttachments as $attachment) {
                if (false === $abstractPage->getAttachments()->contains($attachment)) {
                    $em->remove($attachment);
                }
            }

            foreach ($originalGalleryItems as $item) {
                if (false === $abstractPage->getGallery()->getItems()->contains($item)) {
                    $em->remove($item);
                }
            }

            $em->persist($abstractPage);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($request));
        }

        return [
            'page' => $abstractPage,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param AbstractPage $abstractPage
     *
     * @return RedirectResponse
     */
    protected function handleDelete(Request $request, AbstractPage $abstractPage)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($abstractPage);
        $em->flush();

        return $this->redirect($this->getRedirectUrl($request));
    }
}