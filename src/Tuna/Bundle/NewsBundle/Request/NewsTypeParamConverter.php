<?php

namespace TheCodeine\NewsBundle\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;

class NewsTypeParamConverter implements ParamConverterInterface
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException       When unable to guess how to get a Doctrine instance from the request information
     * @throws NotFoundHttpException When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $id = $request->attributes->get('id');
        $newsType = $request->attributes->get('newsType');

        $news = $this->em->find('TheCodeineNewsBundle:'.$newsType, $id);

        $param = $configuration->getName();
        $request->attributes->set($param, $news);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return "TheCodeine\NewsBundle\Entity\News" === $configuration->getClass();
    }
}
