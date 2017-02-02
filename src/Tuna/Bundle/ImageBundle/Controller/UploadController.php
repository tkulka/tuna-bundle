<?php

namespace TheCodeine\ImageBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TheCodeine\ImageBundle\Entity\Image;
use TheCodeine\ImageBundle\Form\ImageRequestType;
use TheCodeine\ImageBundle\Form\ImageRemoteType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UploadController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function uploadFromRemoteAction(Request $request)
    {
        return $this->handleRequest(new ImageRemoteType(), $request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function uploadFromRequestAction(Request $request)
    {
        return $this->handleRequest(new ImageRequestType(), $request);
    }

    /**
     * @Template("TheCodeineImageBundle:Upload:index.html.twig")
     */
    public function remoteAction()
    {
        $form = $this->createForm(new ImageRemoteType(), null, [
            'method' => 'POST',
            'action' => $this->generateUrl('_image_upload_remote')
        ]);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Template("TheCodeineImageBundle:Upload:index.html.twig")
     */
    public function requestAction()
    {
        $form = $this->createForm(new ImageRequestType(), null, [
            'method' => 'POST',
            'action' => $this->generateUrl('_image_upload_request')
        ]);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param FormTypeInterface $type
     * @param Request $request
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    private function handleRequest(FormTypeInterface $type, Request $request)
    {
        $image = new Image();

        $form = $this->createForm($type, $image, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if (!$form->isValid()) {
            throw $this->createNotFoundException('Invalid request' . $form->getErrorsAsString());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return new Response($this->get('serializer')->serialize(['image' => $image], $request->attributes->get('_format')));
    }
}
