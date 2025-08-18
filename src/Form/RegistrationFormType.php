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
                'label' => 'Username (3-50 characters)',
                'label_attr' => ['class' => 'block mb-2 text-gray-600 font-bold text-sm'],
                'attr' => [
                    'placeholder' => 'Enter username',
                    'class' => '',
                    'style' => 'width: 100% !important; padding: 16px 16px 16px 48px !important; border: 2px solid #d1d5db !important; border-radius: 8px !important; font-size: 16px !important; transition: all 0.3s ease !important; background-color: #f9fafb !important; outline: none !important;',
                    'onfocus' => 'this.style.borderColor="#3b82f6"; this.style.backgroundColor="white"; this.style.boxShadow="0 0 0 3px rgba(59, 130, 246, 0.1)";',
                    'onblur' => 'this.style.borderColor="#d1d5db"; this.style.backgroundColor="#f9fafb"; this.style.boxShadow="none";'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password (min 6 characters)',
                'label_attr' => ['class' => 'block mb-2 text-gray-600 font-bold text-sm'],
                'attr' => [
                    'placeholder' => 'Enter password',
                    'class' => '',
                    'style' => 'width: 100% !important; padding: 16px 16px 16px 48px !important; border: 2px solid #d1d5db !important; border-radius: 8px !important; font-size: 16px !important; transition: all 0.3s ease !important; background-color: #f9fafb !important; outline: none !important;',
                    'onfocus' => 'this.style.borderColor="#3b82f6"; this.style.backgroundColor="white"; this.style.boxShadow="0 0 0 3px rgba(59, 130, 246, 0.1)";',
                    'onblur' => 'this.style.borderColor="#d1d5db"; this.style.backgroundColor="#f9fafb"; this.style.boxShadow="none";'
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'label_attr' => ['class' => 'block mb-2 text-gray-600 font-bold text-sm'],
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Re-enter your password',
                    'class' => '',
                    'style' => 'width: 100% !important; padding: 16px 16px 16px 48px !important; border: 2px solid #d1d5db !important; border-radius: 8px !important; font-size: 16px !important; transition: all 0.3s ease !important; background-color: #f9fafb !important; outline: none !important;',
                    'onfocus' => 'this.style.borderColor="#3b82f6"; this.style.backgroundColor="white"; this.style.boxShadow="0 0 0 3px rgba(59, 130, 246, 0.1)";',
                    'onblur' => 'this.style.borderColor="#d1d5db"; this.style.backgroundColor="#f9fafb"; this.style.boxShadow="none";'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Account',
                'attr' => [
                    'class' => 'w-full py-4 bg-gradient-to-br from-rick-green to-portal-blue text-white border-0 rounded-lg text-base font-semibold cursor-pointer transition-all duration-300 relative overflow-hidden hover:-translate-y-1 hover:shadow-lg active:translate-y-0'
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