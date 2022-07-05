<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
            'label' => 'Mon nouveau mot de passe',
            'required' => true,
            'first_options' => ['label' => 'Mon nouveau mot de passe'],
            'second_options' => ['label' => 'Confirmez votre nouveau mot de passe'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de renseigner un nouveau mot de passe',
                ]),
                new PasswordStrength([
                    'minLength' => 8,
                    'tooShortMessage' => 'Le mot de passe doit contenir au moins {{length}} caractères',
                    'minStrength' => 4,
                    'message'=> 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial'
                ])
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Mettre à jour",
            'attr' => [
                'class' => 'btn btn-info'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
