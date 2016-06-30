<?php

namespace TheCodeine\NewsBundle\Controller;

use TheCodeine\NewsBundle\Entity\Attachment;
use TheCodeine\NewsBundle\Entity\BaseNews;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Form\AttachmentType;
use TheCodeine\NewsBundle\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        return array(
            'entities' => $this->getDoctrine()->getRepository('TheCodeineNewsBundle:BaseNews')->findAll(),
        );
    }

    /**
     *
     * @param Request $request
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(BaseNews $news)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();

        return $this->redirect($this->generateUrl('tuna_news_list', array('newsType' => $news->getType())));
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param string $newsType
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, $newsType)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $this->get('tuna.news.factory')->getInstance($newsType);
        $validate = !$request->isXmlHttpRequest();

        $form = $this->createForm($this->get('tuna.news.factory')->getFormInstance($news, $validate), $news);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($news->getImage()->getFile() == null) {
                $news->setImage(null);
            }
            $em->persist($news);

            if (!$request->isXmlHttpRequest()) {
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_news_list', array('newsType' => $newsType)));
            }
        }

        return array(
            'news' => $news,
            'newsType' => $newsType,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(BaseNews $news, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $validate = !$request->isXmlHttpRequest();

        $form = $this->createForm($this->get('tuna.news.factory')->getFormInstance($news, $validate), $news);

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
                // remove image
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

            $em->persist($news);

            if (!$request->isXmlHttpRequest()) {
                $em->flush();

                return $this->redirect($this->generateUrl('tuna_news_list', array('newsType' => $news->getType())));
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
