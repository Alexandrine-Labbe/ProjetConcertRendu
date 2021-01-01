<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/list/{page}", name="member_index", methods={"GET"}, requirements={"page" = "\d+"}, defaults={"page"=1})
     * @param $page
     * @param MemberRepository $memberRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index($page, MemberRepository $memberRepository, PaginatorInterface $paginator): Response
    {
        $members = $memberRepository->findAll();

        $membersPagine = $paginator->paginate(
            $members,
            $page,
            12
        );

        return $this->render('app/member/index.html.twig', [
            'members' => $membersPagine,
            'page' => 'member',
        ]);
    }

    /**
     * @Route("/new", name="member_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = $fileUploader->upload($pictureFile);

                $member->setPicturePath($this->getParameter('assets_image_directory') . $newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('member_index');
        }

        return $this->render('app/member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="member_show", methods={"GET"})
     * @param Member $member
     * @return Response
     */
    public function show(Member $member): Response
    {
        return $this->render('app/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="member_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Member $member
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Member $member, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = $fileUploader->upload($pictureFile);

                $member->setPicturePath($this->getParameter('assets_image_directory') . $newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('member_index');
        }

        return $this->render('app/member/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="member_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Member $member
     * @return Response
     */
    public function delete(Request $request, Member $member): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('member_index');
    }
}
