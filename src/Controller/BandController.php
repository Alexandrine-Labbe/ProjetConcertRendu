<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use App\Repository\BandRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/band")
 */
class BandController extends AbstractController
{
    /**
     * @Route("/", name="band_index", methods={"GET"})
     * @param BandRepository $bandRepository
     * @return Response
     */
    public function index(BandRepository $bandRepository): Response
    {
        return $this->render('app/band/index.html.twig', [
            'bands' => $bandRepository->findAll(),
            'page' => 'groupe',
        ]);
    }

    /**
     * @Route("/new", name="band_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = $fileUploader->upload($pictureFile);

                $band->setPicturePath($this->getParameter('assets_image_directory') . $newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($band);
            $entityManager->flush();

            return $this->redirectToRoute('band_index');
        }

        return $this->render('app/band/new.html.twig', [
            'band' => $band,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="band_show", methods={"GET"})
     */
    public function show(Band $band): Response
    {
        return $this->render('app/band/show.html.twig', [
            'band' => $band,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="band_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Band $band
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Band $band, FileUploader $fileUploader): Response
    {

        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = $fileUploader->upload($pictureFile);

                $band->setPicturePath($this->getParameter('assets_image_directory') . $newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('band_index');
        }

        return $this->render('app/band/edit.html.twig', [
            'band' => $band,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="band_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Band $band
     * @return Response
     */
    public function delete(Request $request, Band $band): Response
    {
        if ($this->isCsrfTokenValid('delete'.$band->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($band);
            $entityManager->flush();
        }

        return $this->redirectToRoute('band_index');
    }
}
