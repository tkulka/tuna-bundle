<?php

namespace TheCodeine\GalleryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TheCodeine\GalleryBundle\Entity\Gallery;
use TheCodeine\GalleryBundle\Form\GalleryType;

class GalleryController extends Controller
{
    /**
     * @Template()
     *
     * @param Gallery $gallery
     *
     * @return array
     */
    public function editAction(Gallery $gallery)
    {
        return array(
            'form' => $this->createForm(new GalleryType(), $gallery)->createView()
        );
    }

    /**
     * @Template()
     *
     * @param Gallery $gallery
     *
     * @return array
     */
    public function showAction(Gallery $gallery)
    {
        return array(
            'gallery' => $gallery
        );
    }
}
