<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TeamAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Team::class,
            'placeholder' => 'Choisir une équipe',
            'choice_label' => 'name',

            // choose which fields to use in the search
            // if not passed, *all* fields are used
            // 'searchable_fields' => ['name','champs2'],

            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
