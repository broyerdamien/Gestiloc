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
            ->add('loyer')
            ->add('rentalCharges')
            ->add('lodgers', EntityType::class, [
                'class' => Lodger::class,
                'choice_label' => function($lodger) {
                    return $lodger->getName() . ' ' . $lodger->getFirstname();
                },
                'multiple' => true,
                'expanded' => true // mettre à true si vous voulez des checkboxes
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
