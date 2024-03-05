<?php

namespace App\Repository;

use App\Entity\Medecins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medecins>
 *
 * @method Medecins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medecins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medecins[]    findAll()
 * @method Medecins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedecinsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medecins::class);
    }

//    /**
//     * @return Medecins[] Returns an array of Medecins objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Medecins
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
