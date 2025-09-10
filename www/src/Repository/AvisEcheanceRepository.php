<?php

namespace App\Repository;

use App\Entity\AvisEcheance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvisEcheance>
 *
 * @method AvisEcheance|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisEcheance|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisEcheance[]    findAll()
 * @method AvisEcheance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisEcheanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisEcheance::class);
    }


    public function findAllSortedByDateAndLodgerName(): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.location', 'l') // Jointure avec l'entité Location
            ->leftJoin('l.lodgers', 'lodger') // Jointure avec l'entité Lodger (utilisation de leftJoin pour éviter de filtrer les locations sans lodger)
            ->orderBy('a.dateDebut', 'DESC') // Tri par dateDebut décroissante
            ->addOrderBy('lodger.name', 'ASC') // Tri par nom du lodger en ordre alphabétique croissant
            ->getQuery()
            ->getResult();
    }
}
