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
            ->add('type', null, [
                'label' => 'property.show.table.type',
            ])
            ->add('name', null, [
                'label' => 'property.show.table.name',
            ])
            ->add('address', null, [
                'label' => 'property.show.table.address',
            ])
            ->add('building', null, [
                'label' => 'property.show.table.building',
            ])
            ->add('etage', null, [
                'label' => 'property.show.table.floor',
            ])
            ->add('numero', null, [
                'label' => 'property.show.table.number',
            ])
            ->add('country', null, [
                'label' => 'property.show.table.country',
            ])
            ->add('postCode', null, [
                'label' => 'property.show.table.postcode',
            ])
            ->add('area', null, [
                'label' => 'property.show.table.area',
            ])
            ->add('numberOfParts', null, [
                'label' => 'property.show.table.number_of_parts',
            ])
            ->add('bedroom', null, [
                'label' => 'property.show.table.bedroom',
            ])
            ->add('bathroom', null, [
                'label' => 'property.show.table.bathroom',
            ])
            ->add('rentalCharges', null, [
                'label' => 'property.show.table.rental_charges',
            ])
            ->add('lodgers', EntityType::class, [
                'class' => Lodger::class,
                'choice_label' => function (Lodger $lodger) {
                    return $lodger->getName() . ' ' . $lodger->getFirstname();
                },
                'label' => 'lodger.index.title',
                'multiple' => true,
                'expanded' => false, // menu déroulant
                'attr' => ['class' => 'form-select'], // joli rendu bootstrap
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
