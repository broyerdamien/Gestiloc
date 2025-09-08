<?php

namespace App\Repository;

use App\Entity\Lodger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lodger>
 *
 * @method Lodger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lodger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lodger[]    findAll()
 * @method Lodger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LodgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lodger::class);
    }

    public function findAllByAsc()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult() ;
    }
}
