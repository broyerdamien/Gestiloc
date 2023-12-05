<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('loyer')
            ->add('rentalCharges')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
