<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Property;
use App\Entity\Lodger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Type de bail
            ->add('Type', ChoiceType::class, [
                'label' => 'location.form.type',
                'choices' => Location::getTypesChoices(),
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('Depot', null, [
                'label' => 'location.form.deposit',
            ])
            ->add('startDate', null, [
                'label' => 'location.form.start_date',
                'widget' => 'single_text',
            ])
            ->add('endDate', null, [
                'label' => 'location.form.end_date',
                'widget' => 'single_text',
            ])
            ->add('etat', null, [
                'label' => 'location.form.state',
            ])
            ->add('loyer', NumberType::class, [
                'label' => 'location.form.rent',
                'required' => false,
            ])
            ->add('properties', EntityType::class, [
                'class' => Property::class,
                'choice_label' => 'name',
                'label' => 'location.form.properties',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'data-controller' => 'tom-select',
                ],
            ])
            ->add('lodgers', EntityType::class, [
                'class' => Lodger::class,
                'choice_label' => function (Lodger $lodger) {
                    return $lodger->getName().' '.$lodger->getFirstname();
                },
                'label' => 'location.form.lodgers',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'data-controller' => 'tom-select',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
