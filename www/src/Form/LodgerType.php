<?php

namespace App\Form;

use App\Entity\Lodger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Property;

class LodgerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'lodger.index.table.name',
            ])
            ->add('firstname', null, [
                'label' => 'lodger.show.table.firstname',
            ])
            ->add('address', null, [
                'label' => 'lodger.index.table.address',
            ])
            ->add('phone', null, [
                'label' => 'lodger.index.table.phone',
            ])
            ->add('mail', null, [
                'label' => 'lodger.index.table.mail',
            ])
            ->add('dateOfBirth', null, [
                'label' => 'lodger.show.table.date_of_birth',
                'widget' => 'single_text',
            ])
            ->add('job', null, [
                'label' => 'lodger.show.table.job',
            ])
            ->add('salary', null, [
                'label' => 'lodger.index.table.salary',
            ])
            ->add('sex', null, [
                'label' => 'lodger.show.table.sex',
            ])
            ->add('property', EntityType::class, [
                'class' => Property::class,
                'choice_label' => 'name',
                'label' => 'lodger.index.table.property',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodger::class,
        ]);
    }
}
