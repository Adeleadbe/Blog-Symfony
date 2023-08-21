<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(name: 'app_security_')]
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($user);
            if ($errors->count() <= 0) {
                $user->setRoles([]);
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Félicitations, votre compte a bien été créé.');
                return $this->redirectToRoute('app_security_login');
            } else {
                foreach ($errors as $violation) {
                    $this->addFlash('error', $violation->getMessage());
                }
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
