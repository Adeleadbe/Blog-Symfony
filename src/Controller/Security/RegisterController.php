<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_security_')]
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request) 
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        return $this->render('security/register.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}
