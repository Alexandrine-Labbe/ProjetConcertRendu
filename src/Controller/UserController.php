<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('app/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'page' => 'user',
        ]);
    }


    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        if ($user->getId() != $this->getUser()->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        return $this->render('app/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Session $session
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, Session $session, TranslatorInterface  $translator): Response
    {
        if ($user->getId() != $this->getUser()->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $old_pwd = $form->get('oldPassword')->getData();
            $new_pwd = $form->get('newPassword')->getData();

            if ($new_pwd) {
                $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);

                if($checkPass === true) {
                    $new_pwd_encoded = $passwordEncoder->encodePassword($user, $new_pwd);
                    $user->setPassword($new_pwd_encoded);

                } else {
                    $session->getFlashBag()->add('error', $translator->trans('Mot de passe non valide.'));

                    return $this->redirectToRoute("user_edit", ["id" => $user->getId()]);
                }
            }



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('app/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($user->getId() != $this->getUser()->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
