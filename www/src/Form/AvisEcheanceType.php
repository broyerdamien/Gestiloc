<?php

namespace App\Form;

use App\Entity\AvisEcheance;
use App\Entity\Location;
use App\Enum\PaymentStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'input' => 'datetime_immutable',
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'input' => 'datetime_immutable',
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
            ->add('remainingAmount', NumberType::class, [
                'label' => 'Montant restant',
                'required' => false,
                'attr' => [
                    'readonly' => true, // Rendre le champ non modifiable
                ],
            ])
            ->add('partialPaymentAmount', NumberType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Montant du paiement partiel',
                'attr' => [
                    'placeholder' => 'Saisir le montant si paiement partiel',
                ],
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => function (Location $location) {
                    // Si le location a des lodgers, afficher le premier nom, sinon un message
                    return $location->getLodgers()->count() > 0
                        ? $location->getLodgers()->first()->getName()
                        : 'Aucun locataire';
                }, 'label' => 'Location',
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

