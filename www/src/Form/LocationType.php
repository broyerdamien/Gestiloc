<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Property;
use App\Entity\Lodger;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type', ChoiceType::class, [
                'choices' => [
                    'Bail d\'habitation vide' => 'Bail d\'habitation vide',
                    'Bail d\'habitation meublé' => 'Bail d\'habitation meublé',
                    'Bail meublé étudiant' => 'Bail meublé étudiant',
                    'bail location saisonière' => 'bail location saisonière',
                    'bail parking/garage' => 'bail parking/garage',
                    'bail commercial' => 'bail commercial',
                    'bail Professionnel' => 'bail Professionnel',

                ],
                'expanded' => false, // Utilisez `true` pour créer des cases à cocher
                'multiple' => false  // `true` si plusieurs choix sont possibles, `false` pour un seul choix
            ])
            ->add('Depot')
            ->add('startDate')
            ->add('endDate')
            ->add('etat')
            ->add('properties')
            ->add('lodgers')
            ->add('properties', EntityType::class, [
                'class' => Property::class,
                'choice_label' => 'name',
                'multiple' => true, // Permet de sélectionner plusieurs entités
                'expanded' => false  // `true` pour des cases à cocher, `false` pour un menu déroulant
            ])
            ->add('lodgers', EntityType::class, [
                'class' => Lodger::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
