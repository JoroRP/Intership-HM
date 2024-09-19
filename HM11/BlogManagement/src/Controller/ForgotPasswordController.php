<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'forgot_password')]
    public function request(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $resetToken = ByteString::fromRandom(32)->toString();
                $user->setResetToken($resetToken);

                $user->setResetTokenExpiresAt(new \DateTime('+1 minute'));

                $entityManager->flush();

                return $this->redirectToRoute('reset_password', ['token' => $resetToken]);
            } else {
                $this->addFlash('danger', 'This email does not exist.');
            }
        }

        return $this->render('forgot_password/profile.html.twig');
    }

    #[Route('/reset-password', name: 'reset_password')]
    public function reset(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $userRepository->findOneBy(['resetToken' => $data['token']]);

            if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
                $this->addFlash('danger', 'Invalid or expired reset token.');
                return $this->redirectToRoute('reset_password');
            }

            if ($data['password'] !== $form->get('confirm_password')->getData()) {
                $this->addFlash('danger', 'Passwords do not match.');
                return $this->redirectToRoute('reset_password');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);

            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            $entityManager->flush();

            $this->addFlash('success', 'Your password has been successfully reset.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('forgot_password/reset_password.html.twig', [
            'form' => $form->createView(),
            'token' => $request->query->get('token'),
        ]);
    }
}
