<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Lodger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('name')
            ->add('address')
            ->add('building')
            ->add('etage')
            ->add('numero')
            ->add('country')
            ->add('postCode')
            ->add('area')
            ->add('numberOfParts')
            ->add('bedroom')
            ->add('bathroom')
            // ... autres champs ...
            ->add('lodgers', EntityType::class, [
                'class' => Lodger::class,
                'choice_label' => 'name', // ou toute autre propriété que vous souhaitez afficher
                'multiple' => true, // Permet de sélectionner plusieurs lodgers
                'expanded' => true, // Affiche sous forme de cases à cocher
            ]);
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}