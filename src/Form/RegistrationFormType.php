<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username (3-50 characters)',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 50])
                ],
                'attr' => [
                    'placeholder' => 'Enter Username',
                    'class' => 'form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password (min 6 characters)',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6])
                ],
                'attr' => [
                    'placeholder' => 'Enter Password',
                    'class' => 'form-control'
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'required' => true,
                'mapped' => false, // Don't save this field to User entity
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Re-enter Password',
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Account',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\User::class,
            // No constraints here - password matching handled in controller
        ]);
    }
}