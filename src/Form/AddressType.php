<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label'=> 'Quel nom souhaitez-vous donner à votre adresse ?'
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'Votre prénom'
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Votre nom'
            ])
            ->add('compagny', TextType::class, [
                'label'=> 'Si vous avez une société, entrez son nom',
                'required'=> false,
                'attr' => [
                    'placeholder' => 'Entrez le nom de votre société (facultatif)'
                ]
            ])
            ->add('address', TextType::class, [
                'label'=> 'Votre adresse'
            ])
            ->add('postal', TextType::class, [
                'label'=> 'Code postal'
            ])
            ->add('city', TextType::class, [
                'label'=> 'Ville'
            ])
            ->add('country', CountryType::class, [
                'label'=> 'Pays'
            ])
            ->add('phone', TelType::class, [
                'label'=> 'Votre numéro'
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
