<?php

namespace App\Repository;

use App\Entity\PlanningMedecins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanningMedecins>
 *
 * @method PlanningMedecins|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanningMedecins|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanningMedecins[]    findAll()
 * @method PlanningMedecins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningMedecinsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningMedecins::class);
    }

//    /**
//     * @return PlanningMedecins[] Returns an array of PlanningMedecins objects
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

//    public function findOneBySomeField($value): ?PlanningMedecins
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}