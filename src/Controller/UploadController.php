<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{
    public function upload(Request $request): Response
{
    $form = $this->createForm(FileUploadType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('file')->getData();
        $newFilename = uniqid().'.'.$file->guessExtension();
        $file->move($this->getParameter('uploads_directory'), $newFilename);
    }

    return $this->render('upload.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
