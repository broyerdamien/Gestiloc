<?php

namespace App\Repository;

use App\Entity\AvisEcheance;
use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 *
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function getTotalPaidForAvisEcheance(AvisEcheance $avisEcheance): float
    {
        try {
            $totalPaid = $this->createQueryBuilder('p')
                ->select('SUM(p.amount)')
                ->where('p.avisEcheance = :avisEcheance')
                ->setParameter('avisEcheance', $avisEcheance)
                ->getQuery()
                ->getSingleScalarResult();

            return (float)($totalPaid ?? 0);
        } catch (NoResultException|NonUniqueResultException $e) {
            // En cas d'erreur, retourne 0 comme total par défaut
            return 0.0;
        }
    }
}
