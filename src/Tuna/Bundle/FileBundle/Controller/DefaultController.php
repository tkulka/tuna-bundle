<?php

namespace TunaCMS\Bundle\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TunaCMS\Bundle\FileBundle\Form\UploadedFileType;

/**
 * @Route("/file")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/upload/", options={"expose"=true}, name="tuna_file_upload")
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(UploadedFileType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->getErrorResponse($form);
        } else {
            $file = $form->get('file')->getData();
            $fileInfo = $this->getFileInfo($file);

            try {
                $this->get('tuna_cms_file.manager.file_manager')->moveUploadedFile($file, $fileInfo['path']);
            } catch (FileException $e) {
                $translator = $this->get('translator.default');
                $errorMsg = $translator->trans('error.upload.cant_move', [], 'validators');

                return new JsonResponse([
                    'messages' => [$errorMsg]
                ]);
            }
            return new JsonResponse($fileInfo);
        }
    }

    /**
     * @param FormInterface $form
     *
     * @return JsonResponse
     */
    private function getErrorResponse(FormInterface $form)
    {
        $errors = $form->getErrors();
        $errorCollection = [];

        foreach ($errors as $error) {
            $errorCollection[] = $error->getMessage();
        }

        return new JsonResponse([
            'messages' => $errorCollection,
        ], 400);
    }

    /**
     * @param UploadedFile $file
     *
     * @return array
     */
    private function getFileInfo(UploadedFile $file)
    {
        $fileManager = $this->get('tuna_cms_file.manager.file_manager');
        $filename = $fileManager->generateTmpFilename($file);

        return [
            'path' => $filename,
            'mimeType' => $file->getMimeType(),
            'originalName' => $file->getClientOriginalName(),
        ];
    }
}
