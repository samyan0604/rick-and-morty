<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Username',
                    'maxlength' => 50,
                    'minlength' => 3,
                    'class' => 'form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Password (min 6 characters)',
                    'minlength' => 6,
                    'class' => 'form-control'
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'required' => true,
                'mapped' => false, // Don't save this field to User entity
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
        ]);
    }
}