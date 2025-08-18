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
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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

        // Check if form was submitted
        if ($form->isSubmitted()) {
            // Get form data
            $username = $user->getUsername();
            $password = $user->getPassword();
            $confirmPassword = $form->get('confirmPassword')->getData();
            
            // Validate username
            if (empty($username)) {
                $this->addFlash('error', 'Username cannot be empty');
                return $this->redirectToRoute('app_register');
            } elseif (strlen($username) < 3) {
                $this->addFlash('error', 'Username must be at least 3 characters long');
                return $this->redirectToRoute('app_register');
            } elseif (strlen($username) > 50) {
                $this->addFlash('error', 'Username cannot be longer than 50 characters');
                return $this->redirectToRoute('app_register');
            }
            
            // Validate password
            if (empty($password)) {
                $this->addFlash('error', 'Password cannot be empty');
                return $this->redirectToRoute('app_register');
            } elseif (strlen($password) < 6) {
                $this->addFlash('error', 'Password must be at least 6 characters long');
                return $this->redirectToRoute('app_register');
            }
            
            // Validate password confirmation
            if (empty($confirmPassword)) {
                $this->addFlash('error', 'Please confirm your password');
                return $this->redirectToRoute('app_register');
            } elseif ($password !== $confirmPassword) {
                $this->addFlash('error', 'Passwords do not match');
                return $this->redirectToRoute('app_register');
            }
            
            // If no validation errors, proceed with registration
            try {
                // Hash the password before saving
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                
                // Set the hashed password on the user
                $user->setPassword($hashedPassword);
                
                // Save user to database
                $entityManager->persist($user);
                $entityManager->flush();
                
                // Success! Redirect to login with success message
                $this->addFlash('success', 'Registration successful! You can now log in.');
                return $this->redirectToRoute('app_login');
                
            } catch (UniqueConstraintViolationException $e) {
                // Handle unique constraint violations (duplicate username)
                $this->addFlash('error', 'Username already exists! Please choose a different username.');
                return $this->redirectToRoute('app_register');
            } catch (\Exception $e) {
                // Handle other database errors
                $this->addFlash('error', 'Registration failed. Please try again.');
                return $this->redirectToRoute('app_register');
            }

        }

        // Show the registration form (whether first visit or has errors)
        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}