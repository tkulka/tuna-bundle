<?php

namespace TheCodeine\TranslationBundle\Controller;

use Lexik\Bundle\TranslationBundle\Controller\RestController as LexikRestController;
use Symfony\Component\HttpFoundation\Request;

class RestController extends LexikRestController
{
    public function updateAction(Request $request, $id)
    {
        $response = parent::updateAction($request, $id);
        $this->get('lexik_translation.translator')->removeLocalesCacheFiles($this->get('lexik_translation.locale.manager')->getLocales());

        return $response;
    }
}
