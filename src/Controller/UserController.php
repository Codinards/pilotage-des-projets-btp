<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
#[IsGranted(RoleVoter::ADMIN)]
class UserController extends BaseController
{
    #[Route('/{_locale}', name: 'app_user_index', methods: ['GET'], requirements: ["_locale" => "fr|en|es"])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'user/' . ($toPDF ? 'pdf' : 'index') . '.html.twig';

        return $this->render($templatePath, [
            'users' => $userRepository->findBy(['isLocked' => false]),
        ]);
    }

    #[Route('/{_locale}/index/locked', name: 'app_user_index_lock', methods: ['GET'], requirements: ["_locale" => "fr|en|es"])]
    public function indexLocked(Request $request, UserRepository $userRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'user/' . ($toPDF ? 'pdf_locked' : 'locked') . '.html.twig';

        return $this->render($templatePath, [
            'users' => $userRepository->findBy(['isLocked' => true]),
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $userRepository->add($user, true);
            $this->addFlash('success', 'the user has been successfully created.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // #[Route('/{_locale}/{id}', name: 'app_user_show', methods: ['GET'], requirements: ["id" => '\d+'])]
    // public function show(User $user): Response
    // {
    //     return $this->render('user/show.html.twig', [
    //         'user' => $user,
    //     ]);
    // }

    #[Route('/{_locale}/user/import-database', name: 'app_import_database', methods: ['POST'])]
    public function importDatabase(): Response
    {
        return $this->file(APP_DIR . '/var/data.sql');
    }

    #[Route('/{_locale}/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        UserRepository $userRepository
    ): Response {
        $oldUser = clone $user;
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getId() === $this->getUser()->getId()) {
                $user->setRole($oldUser->getRole());
            }
            $userRepository->add($user, true);
            $this->addFlash('success', 'the user has been successfully updated.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'password' => false
        ]);
    }

    #[Route('/{_locale}/{id}/edit-password', name: 'app_user_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(
        Request $request,
        User $user,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher
    ): Response {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPasswordEdit()));
            $userRepository->add($user, true);
            $this->addFlash('success', 'the password has been successfully updated.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'password' => true
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_user_delete', methods: ["GET", 'POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            if ($user->isLocked()) {
                if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                    $userRepository->remove($user, true);
                    $this->addFlash('success', 'the user has been successfully removed.');
                }
            } else {
                $this->addFlash('error', "Cet utilisateur n'a pas bloqué. Il ne peut donc pas être supprimé.");
            }

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/delete.html.twig', ['user' => $user]);
    }

    #[Route('/{_locale}/{id}/lock', name: 'app_user_lock', methods: ['POST'])]
    public function lock(Request $request, User $user, UserRepository $userRepository)
    {
        if ($user->getId() !== $this->getUser()->getId()) {
            if ($this->isCsrfTokenValid('lock' . $user->getId(), $request->request->get('_token'))) {
                $user->setIsLocked(true);
                $userRepository->add($user, true);
                $this->addFlash('success', 'the user has been successfully locked.');
                return $this->redirectToRoute('app_user_index_lock', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{_locale}/{id}/unlock', name: 'app_user_unlock', methods: ['POST'])]
    public function unlock(Request $request, User $user, UserRepository $userRepository)
    {
        if ($this->isCsrfTokenValid('unlock' . $user->getId(), $request->request->get('_token'))) {
            $user->setIsLocked(false);
            $userRepository->add($user, true);
            $this->addFlash('success', 'the user has been successfully unlocked.');
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getUser(): ?User
    {
        return parent::getUser();
    }
}
