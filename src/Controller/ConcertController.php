<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/concert")
 */
class ConcertController extends AbstractController
{
    /**
     * @Route("/list/{year}", name="concert_index", methods={"GET"}, requirements={"year" = "\d+"}, defaults={"year"=2020})
     * @param $year
     * @param ConcertRepository $concertRepository
     * @return Response
     */
    public function index($year, ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->findByYear($year);

        return $this->render('app/concert/index.html.twig', [
            'concerts' => $concerts,
            'page' => 'concert',
        ]);
    }

    /**
     * @Route("/new", name="concert_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concert);
            $entityManager->flush();

            return $this->redirectToRoute('concert_index');
        }

        return $this->render('app/concert/new.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="concert_show", methods={"GET"})
     * @param Concert $concert
     * @return Response
     */
    public function show(Concert $concert): Response
    {
        return $this->render('app/concert/show.html.twig', [
            'concert' => $concert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="concert_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Concert $concert
     * @return Response
     */
    public function edit(Request $request, Concert $concert): Response
    {
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('concert_index');
        }

        return $this->render('app/concert/edit.html.twig', [
            'concert' => $concert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="concert_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Concert $concert
     * @return Response
     */
    public function delete(Request $request, Concert $concert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$concert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($concert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('concert_index');
    }
}
