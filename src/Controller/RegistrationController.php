<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    /**
     * Registration page - show form and handle registration
     */
    #[Route('/registration', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
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
                // Passwords don't match
                $this->addFlash('error', 'Passwords do not match');
            } else {
                try {
                    // Hash the password before saving
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $user->getPassword()  // Plain text password from form
                    );
                    
                    // Set the hashed password on the user
                    $user->setPassword($hashedPassword);
                    
                    // Save user to database
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    // Success! Redirect to login with success message
                    $this->addFlash('success', 'Registration successful! You can now log in.');
                    return $this->redirectToRoute('app_login');
                    
                } catch (\Exception $e) {
                    // Handle database errors
                    $this->addFlash('error', 'Registration failed. Please try again.');
                }
            }
        }

        // Show the registration form (whether first visit or has errors)
        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}