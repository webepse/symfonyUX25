<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => "First Name",
                'attr' => [
                    'placeholder' => "Prénom du joueur"
                ]
            ])
            ->add('lastName', TextType::class,  [
                'label' => "Last Name",
                'attr' => [
                    'placeholder' => "Nom du joueur"
                ]
            ])
            ->add('number', IntegerType::class,  [
                'label' => "Number",
                'attr' => [
                    'placeholder' => "Numéro de maillot"
                ]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label' => "BirthDay",
                'attr' => [
                    'placeholder' => "Date de naissance"
                ]
            ])
            ->add('picture', TextType::class, [
                'label' => "Image du joueur"
            ])

            ->add('team', TeamAutocompleteField::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
