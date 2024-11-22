<?php

namespace App\Form;

use App\Entity\AvisEcheance;
use App\Entity\Location;
use App\Enum\PaymentStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AvisEcheanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text', // Affiche un sélecteur de date
                'label' => 'Date de début',
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            ->add('paymentStatus', ChoiceType::class, [
                'choices' => [
                    'En attente' => PaymentStatus::EN_ATTENTE,
                    'Partiel' => PaymentStatus::PARTIEL,
                    'Payé' => PaymentStatus::PAYE,
                ],
                'label' => 'Statut de paiement',
            ])
            ->add('amount', null, [
                'label' => 'Montant',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => function (Location $location) {
                    // Si le location a des lodgers, afficher le premier nom, sinon un message
                    return $location->getLodgers()->count() > 0
                        ? $location->getLodgers()->first()->getName()
                        : 'Aucun locataire';
                },                'label' => 'Location',
                'placeholder' => 'Choisir une location',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvisEcheance::class,
        ]);
    }
}

