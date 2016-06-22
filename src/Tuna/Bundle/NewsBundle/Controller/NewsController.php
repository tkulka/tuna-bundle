<?php

namespace TheCodeine\NewsBundle\Controller;

use Doctrine\ORM\EntityManager;
use TheCodeine\NewsBundle\Entity\Attachment;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\NewsTranslation;
use TheCodeine\NewsBundle\Form\AttachmentType;
use TheCodeine\NewsBundle\Form\NewsType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Domain\DoctrineAclCache;

use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Gedmo\Translatable\TranslatableListener;

class NewsController extends Controller
{
    /**
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()->getRepository('TheCodeineNewsBundle:News')->getListQuery();
        $page = $request->get('page', 1);
        $limit = 10;

        return array(
            'pagination' => $this->get('knp_paginator')->paginate($query, $page, $limit),
            'offset' => ($page - 1) * $limit,
        );
    }

    /**
     *
     * @param News $news
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(News $news)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();

        return $this->redirect($this->generateUrl('tuna_news_list'));
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $news = new News();

        $form = $this->createForm(new NewsType(), $news);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($news->getImage()->getFile() == null) {
                $news->setImage(null);
            }
            if (!$request->isXmlHttpRequest()) {
                $em->persist($news);
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_news_edit', array('id' => $news->getId())));
            }
        }

        return array(
            'news' => $news,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param News $news
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, News $news)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if (null == $news) {
            $news = new News();
        }

        $form = $this->createForm(new NewsType(), $news);

        $originalAttachments = new ArrayCollection();
        foreach ($news->getAttachments() as $attachment) {
            $originalAttachments[] = $attachment;
        }

        $originalGalleryItems = new ArrayCollection();
        if ($news->getGallery()) {
            foreach ($news->getGallery()->getItems() as $items) {
                $originalGalleryItems[] = $items;
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('image')->get('remove')->getData() == '1') {
                $em->remove($news->getImage());
                $news->setImage(null);
            }
            //remove attachments
            foreach ($originalAttachments as $attachment) {
                if (false === $news->getAttachments()->contains($attachment)) {
                    $em->remove($attachment);
                }
            }

            foreach ($originalGalleryItems as $items) {
                if (false === $news->getGallery()->getItems()->contains($items)) {
                    $em->remove($items);
                }
            }

            if (!$request->isXmlHttpRequest()) {
                $em->persist($news);
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_news_edit', array('id' => $news->getId())));
            }
        }

        return array(
            'news' => $news,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param News $news
     *
     * @return array
     */
    public function showAction(Request $request, News $news)
    {
        return array(
            'news' => $news
        );
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     * @param News $news
     *
     * @return array
     */
    public function addAttachmentAction(Request $request, News $news)
    {
        $attachment = new Attachment();
        $form = $this->createForm(new AttachmentType(), $attachment);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $news->addAttachment($attachment);
            $em->persist($news);
            $em->persist($attachment);
            $em->flush();
            return $this->redirect($this->generateUrl('tuna_news_show', array('id' => $news->getId())));
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
