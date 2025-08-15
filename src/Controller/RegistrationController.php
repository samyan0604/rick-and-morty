<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    /**
     * Registration page - show form and handle registration
     */
    #[Route('/registration', name: 'app_register')]
    public function register(Request $request): Response
    {
        // If user is already logged in, redirect to character list
        if ($this->getUser()) {
            return $this->redirectToRoute('app_character_list');
        }

        // Create empty user entity
        $user = new User();

        // Create form using our form type
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Handle the request (process form submission)
        $form->handleRequest($request);

        // Check if form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the password confirmation for validation
            $confirmPassword = $form->get('confirmPassword')->getData();
            
            // Basic password confirmation check
            if ($user->getPassword() !== $confirmPassword) {
                // Passwords don't match - we'll add proper error handling later
                $this->addFlash('error', 'Passwords do not match');
            } else {
                // Passwords match - we'll add password hashing and database saving later
                
                // For now, just show success and redirect to login
                $this->addFlash('success', 'Registration successful! Please log in.');
                return $this->redirectToRoute('app_login');
            }
        }

        // Show the registration form (whether first visit or has errors)
        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}