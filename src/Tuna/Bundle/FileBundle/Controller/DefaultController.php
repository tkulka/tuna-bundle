<?php

namespace TheCodeine\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TheCodeine\FileBundle\Form\UploadedFileType;

/**
 * @Route("/file")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/upload/")
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(UploadedFileType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $file = $form->get('file')->getData();
            $fileInfo = $this->getFileInfo($file, $file->getClientOriginalName());

            try {
                $this->moveUploadedFile($file, $fileInfo['fileName']);
            } catch (FileException $e) {
                return new JsonResponse(array('messages' => array('Tmp file cannot be moved')));
            }
            return new JsonResponse($fileInfo);
        } else {
            return $this->getErrorResponse($form);
        }
    }

    /**
     * @Route("/remote/")
     */
    public function remoteAction(Request $request)
    {
        return new JsonResponse(array(
            'messages' => array('Remote files are not implemented yet'),
        ), 400);
    }

    private function getErrorResponse(FormInterface $form)
    {
        $errors = $form->getErrors();
        $errorCollection = array();
        foreach ($errors as $error) {
            $errorCollection[] = $error->getMessage();
        }

        return new JsonResponse(array(
            'messages' => $errorCollection,
        ), 400);
    }

    private function getFileInfo(File $file, $originalName)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        return array(
            'path' => sprintf(
                '%s/%s',
                $this->getParameter('tmp_files_path'),
                $fileName
            ),
            'originalName' => $originalName,
            'mimeType' => $file->getMimeType(),
            'fileName' => $fileName,
        );
    }

    /**
     * @param UploadedFile $file
     * @param $fileName
     */
    private function moveUploadedFile(UploadedFile $file, $fileName)
    {
        $file->move(
            sprintf(
                '%s/%s',
                $this->getParameter('web.root_dir'),
                $this->getParameter('tmp_files_path')
            ),
            $fileName
        );
    }
}
