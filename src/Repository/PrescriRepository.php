<?php

namespace App\Repository;

use App\Entity\Prescri;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prescri>
 *
 * @method Prescri|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prescri|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prescri[]    findAll()
 * @method Prescri[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrescriRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prescri::class);
    }

//    /**
//     * @return Prescri[] Returns an array of Prescri objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Prescri
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
