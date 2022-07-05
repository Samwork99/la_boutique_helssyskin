<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Votre adresse email'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
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
                'label' => "Modification du mot de passe"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
