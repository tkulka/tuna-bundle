<?php

namespace TheCodeine\NewsBundle\Controller;

use Doctrine\ORM\EntityManager;
use TheCodeine\NewsBundle\Entity\Attachment;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\NewsBundle\Entity\Event;
use TheCodeine\NewsBundle\Entity\Category;
use TheCodeine\NewsBundle\Entity\NewsTranslation;
use TheCodeine\NewsBundle\Form\AttachmentType;
use TheCodeine\NewsBundle\Form\CategoryType;
use TheCodeine\NewsBundle\Form\NewsType;
use TheCodeine\NewsBundle\Form\EventType;

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

    private static $NEWS_TYPES = array(
        'News' => 'aktualnosci',
        'Event' => 'wydarzenia',
    );

    /**
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function listAction(Request $request, $newsType)
    {
        $categoryId = $request->get('cid');
        $em = $this->getDoctrine()->getManager();
        if ($categoryId) {
            $query = $em
                ->createQuery('SELECT n FROM TheCodeineNewsBundle:$newsType n WHERE n.category = :category ORDER BY n.createdAt DESC')
                ->setParameter('category', $categoryId);
        } else {
            $query = $em
                ->createQuery('SELECT n FROM TheCodeineNewsBundle:$newsType n ORDER BY n.createdAt DESC');
        }
        $pages = $query->getResult();

        return array(
            'newsList' => $pages,
            'newsType' => $newsType,
        );
    }

    /**
     *
     * @param Request $request
     * @param string $newsType
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $newsType, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->find('TheCodeineNewsBundle:' . $newsType, $id);
        $em->remove($news);
        $em->flush();

        return $this->redirect($this->generateUrl('tuna_news_list', array('newsType' => $newsType)));
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
        $news = $this->get('tuna.news.factory')->getNewsInstance($newsType);

        $news->setCategory($this->get('tuna.news.factory')->getCategoryByNewsType($newsType));

        $form = $this->createForm($this->get('tuna.news.factory')->getNewsTypeInstance($newsType), $news);

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
     * @param string $newsType
     * @param integer $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $newsType, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->find('TheCodeineNewsBundle:'.$newsType, $id);

        $form = $this->createForm($this->get('tuna.news.factory')->getNewsTypeInstance($newsType), $news);

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
