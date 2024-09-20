<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\UpdateUserRolesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function viewAndEditProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated successfully.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin-panel', name: 'admin_panel')]
    public function manageUsers(Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $selectedUser = null;
        $form = null;

        $userId = $request->query->get('user') ?? $request->request->get('user');

        if ($userId && is_numeric($userId)) {
            $selectedUser = $entityManager->getRepository(User::class)->find($userId);
        }

        if ($selectedUser) {
            $form = $this->createForm(UpdateUserRolesType::class, $selectedUser);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $submittedRoles = $form->get('roles')->getData();

                if (empty($submittedRoles)) {
                    $this->addFlash('danger', 'The user must have at least one role.');
                } else {
                    $selectedUser->setRoles($submittedRoles);
                    $entityManager->persist($selectedUser);
                    $entityManager->flush();

                    $this->addFlash('success', 'User roles updated successfully.');
                }

                return $this->redirectToRoute('admin_panel', ['user' => $selectedUser->getId()]);
            }
        }

        return $this->render('profile/admin.html.twig', [
            'users' => $users,
            'selectedUser' => $selectedUser,
            'form' => $form ? $form->createView() : null,
        ]);
    }
}
