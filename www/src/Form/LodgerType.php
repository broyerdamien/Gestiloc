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
            ->add('name')
            ->add('firstname')
            ->add('address')
            ->add('phone')
            ->add('mail')
            ->add('dateOfBirth')
            ->add('job')
            ->add('salary')
            ->add('sex')
            ->add('property', EntityType::class, [
                'class' => Property::class,
                'choice_label' => 'name', // ou tout autre attribut de Property que vous souhaitez afficher
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodger::class,
        ]);
    }
}