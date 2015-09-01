<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;

use TheCodeine\NewsBundle\Entity\Category,
    TheCodeine\NewsBundle\Entity\News,
    TheCodeine\NewsBundle\Entity\Attachment,
    TheCodeine\NewsBundle\Entity\NewsTranslation,
    TheCodeine\NewsBundle\Form\AttachmentType,
    TheCodeine\NewsBundle\Form\CategoryType,
    TheCodeine\NewsBundle\Form\NewsType;

class NewsController extends Controller
{
    /**
     * @Route("/admin/news/{cid}/list", name="thecodeine_admin_news_list")
     * @ParamConverter("category", class="TheCodeineNewsBundle:Category", options={"id" = "cid"})
     * @Template()
     */
    public function listAction(Category $category)
    {
        $page = $this->get('request')->query->get('page', 1);
        $limit = 10;

        $query = $this->getDoctrine()->getRepository('TheCodeineNewsBundle:News')->getListQuery(false, $category);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return array(
            'category'  => $category,
            'pagination'=> $pagination,
            'lp'        => $page * $limit - $limit
        );
    }

    /**
     * @Route("/admin/news/{id}/delete", requirements={"id" = "\d+"}, name="thecodeine_admin_news_delete")
     * @Template()
     */
    public function deleteAction(News $news)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/admin/news/{newsId}/edit", name="thecodeine_admin_news_edit")
     * @Template()
     */
    public function editAction($newsId)
    {

        /** @var EntityManager $em */
        $em                 = $this->get('doctrine.orm.entity_manager');
        $news               = null;
        $request            = $this->get('request');
        $categoryId         = $request->get('cid');
        $categoryRepository = $em->getRepository('TheCodeineNewsBundle:Category');
        $newsRepository     = $em->getRepository('TheCodeineNewsBundle:News');

        if($categoryId) {
            $category = $categoryRepository->findOneById($categoryId);
        }

        if($newsId) {
            $news = $newsRepository->findOneById($newsId);
            $category = $news->getCategory();
        }

        // set category for request (for rendering menu)
        $this->get('request')->query->add(array(
            'cid' => $category->getId()
        ));

        if (null == $news) {
            $news = new News();
            $news->setCategory($category);
        }

        $form = $this->createForm(new NewsType(), $news);

        $originalAttachments =  new ArrayCollection();
        foreach($news->getAttachments() as $attachment) {
            $originalAttachments[] = $attachment;
        }

        $originalGalleryImages = new ArrayCollection();
        if($news->getGallery()) {
            foreach($news->getGallery()->getImages() as $image) {
                $originalGalleryImages[] = $image;
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            //remove attachments
            foreach($originalAttachments as $attachment) {
                if (false === $news->getAttachments()->contains($attachment)) {
                    $em->remove($attachment);
                }
            }

            foreach($originalGalleryImages as $image) {
                if (false === $news->getGallery()->getImages()->contains($image)) {
                    $em->remove($image);
                }
            }

            $em->persist($news);
            $em->flush();

            if ($news->isImportant()) {
                foreach($newsRepository->getLastItemsForCategoryWithChildrenQuery($news->getCategory()->getParent(), true) as $featuredNews) {
                    if ($featuredNews->getId() != $news->getId()) {
                        $featuredNews->setImportant(false);
                    }
                }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('thecodeine_admin_news_edit', array('newsId' => $news->getId())));
        }

        return array(
            'news' => $news,
            'form' => $form->createView(),
            'category' => $category
        );

    }
}