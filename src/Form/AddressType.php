<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Quel nom souhaitez-vous donner à votre adresse ?',
                'attr' => [
                    'placeholder' => 'Ex : Adresse principale',
                    'autofocus' => 'name'
                ],
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 30,
                ]),
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Saississez votre prénom'
                ],
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 30,
                ]),
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Votre nom',
                'attr' => [
                    'placeholder' => 'Saississez votre nom'
                ],
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 30,
                ]),
            ])
            ->add('compagny', TextType::class, [
                'label'=> 'Si vous avez une société, entrez son nom',
                'required'=> false,
                'attr' => [
                    'placeholder' => 'Entrez le nom de votre société (facultatif)'
                ]
            ])
            ->add('address', TextType::class, [
                'label'=> 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse valide'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('postal', TextType::class, [
                'label'=> 'Code postal',
                'attr' => [
                    'placeholder' => 'Entrez votre code postal',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un code postal valide',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre code postal doit faire au moins {{ limit }} caractères',
                        'max' => 5,
                        'maxMessage' => 'Votre code postal doit faire au maximum {{ limit }} caractères',
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label'=> 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner la ville correspondante',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                    ]),
                ]
            ])
            ->add('country', CountryType::class, [
                'label'=> 'Pays',
            ])
            ->add('phone', TelType::class, [
                'label'=> 'Votre numéro',
                'attr' => [
                    'placeholder' => 'Saisir votre numéro',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
