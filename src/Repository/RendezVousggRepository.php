<?php

namespace App\Repository;

use App\Entity\RendezVousgg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVousgg>
 *
 * @method RendezVousgg|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVousgg|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVousgg[]    findAll()
 * @method RendezVousgg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousggRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVousgg::class);
    }

//    /**
//     * @return RendezVousgg[] Returns an array of RendezVousgg objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RendezVousgg
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
