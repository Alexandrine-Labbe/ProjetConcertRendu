<?php


namespace App\Controller;


use App\Repository\ConcertRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/{page}", name="home", requirements={"page" = "\d+"}, defaults={"page"=1})
     * @param $page
     * @param ConcertRepository $concertRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function list($page, ConcertRepository $concertRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $futureConcert = $concertRepository->findByAfterDate(new DateTime());

        $futureConcertPagine = $paginator->paginate(
            $futureConcert,
            $page,
            9
        );
        dump($request->getSession()->get('_locale', ""));

        return $this->render('app/portail.html.twig', [
            'page' => 'Home',
            'future_concert' => $futureConcertPagine,

        ]);
    }
}
