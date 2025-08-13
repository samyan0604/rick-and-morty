<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Login page - displays login form and handles form submission
     */
    #[Route('/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If user is already logged in, redirect to character list
        if ($this->getUser()) {
            return $this->redirectToRoute('app_character_list');
        }

        // Get any login error (wrong username/password)
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Get the last username entered (so form remembers it)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout route - Symfony handles this automatically
     * This method will never be executed because Symfony intercepts the request
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method can be blank - Symfony handles logout automatically
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}