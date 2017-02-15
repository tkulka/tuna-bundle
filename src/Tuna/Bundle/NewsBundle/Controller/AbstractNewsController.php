<?php

namespace TheCodeine\NewsBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TheCodeine\NewsBundle\Entity\AbstractNews;

abstract class AbstractNewsController extends Controller
{
    /**
     * @param string $newsType
     *
     * @return mixed
     */
    abstract public function getNewNews($newsType);

    /**
     * @param AbstractNews $abstractNews
     *
     * @return mixed
     */
    abstract public function getFormType(AbstractNews $abstractNews);

    /**
     * @param Request $request
     * @param AbstractNews $abstractNews
     *
     * @return string
     */
    abstract public function getRedirectUrl(Request $request, AbstractNews $abstractNews);

    /**
     * @return EntityRepository
     */
    abstract public function getRepository();

    /**
     * @Route("/{newsType}/list", name="tuna_news_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [
            'entities' => $this->getRepository()->findAll(),
        ];
    }

    /**
     * @Route("/create", name="tuna_news_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $newsType = $request->query->get('newsType');
        $abstractNews = $this->getNewNews($newsType);

        $form = $this->createForm($this->get('the_codeine_news.factory')->getFormInstance($abstractNews), $abstractNews);

        // TODO: Move this to twig
        $form->add('save', SubmitType::class, [
            'label' => 'ui.form.labels.save'
        ]);

        $result = $this->handleCreate($request, $form, $abstractNews);

        if (is_array($result)) {
            $result['newsType'] = $newsType;
        }

        return $result;
    }

    /**
     * @Route("/{id}/edit", name="tuna_news_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, AbstractNews $abstractNews)
    {
        $originalAttachments = new ArrayCollection();
        foreach ($abstractNews->getAttachments() as $attachment) {
            $originalAttachments[] = $attachment;
        }

        $originalGalleryItems = new ArrayCollection();
        if ($abstractNews->getGallery()) {
            foreach ($abstractNews->getGallery()->getItems() as $item) {
                $originalGalleryItems[] = $item;
            }
        }

        $form = $this->createForm($this->getFormType($abstractNews), $abstractNews);

        // TODO: Move this to twig
        $form->add('save', SubmitType::class, [
            'label' => 'ui.form.labels.save'
        ]);

        return $this->handleEdit($request, $form, $abstractNews, $originalAttachments, $originalGalleryItems);
    }

    /**
     * @Route("/{id}/delete", name="tuna_news_delete", requirements={"id" = "\d+"})
     * @Template()
     */
    public function deleteAction(Request $request, AbstractNews $abstractNews)
    {
        return $this->handleDelete($request, $abstractNews);
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param AbstractNews $abstractNews
     *
     * @return array|RedirectResponse
     */
    protected function handleCreate(Request $request, Form $form, AbstractNews $abstractNews)
    {
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abstractNews);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($request, $abstractNews));
        }

        return [
            'page' => $abstractNews,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param AbstractNews $abstractNews
     * @param ArrayCollection $originalAttachments
     * @param ArrayCollection $originalGalleryItems
     *
     * @return array|RedirectResponse
     */
    protected function handleEdit(Request $request, Form $form, AbstractNews $abstractNews, ArrayCollection $originalAttachments, ArrayCollection $originalGalleryItems)
    {
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalAttachments as $attachment) {
                if (false === $abstractNews->getAttachments()->contains($attachment)) {
                    $em->remove($attachment);
                }
            }

            foreach ($originalGalleryItems as $item) {
                if (false === $abstractNews->getGallery()->getItems()->contains($item)) {
                    $em->remove($item);
                }
            }

            $em->persist($abstractNews);
            $em->flush();

            return $this->redirect($this->getRedirectUrl($request, $abstractNews));
        }

        return [
            'page' => $abstractNews,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param AbstractNews $abstractNews
     *
     * @return RedirectResponse
     */
    protected function handleDelete(Request $request, AbstractNews $abstractNews)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($abstractNews);
        $em->flush();

        return $this->redirect($this->getRedirectUrl($request, $abstractNews));
    }
}