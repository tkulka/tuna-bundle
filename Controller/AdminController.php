<?php

namespace TheCodeine\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use TheCodeine\NewsBundle\Entity\Category;

class AdminController extends Controller
{
    /**
     *
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction()
    {
        $categoryId = $this->get('request')->get('cid',null);

        if(!$categoryId) {
            $category = $this->getDoctrine()->getRepository('TheCodeineNewsBundle:Category')->findOneBy(array(
                'lvl' => 0
            ));

            if(!$category) {
                return $this->createNotFoundException('No category found');
            }

            $categoryId = $category->getId();
        }

        return array(
            'cid' => $categoryId
        );

    }
}
