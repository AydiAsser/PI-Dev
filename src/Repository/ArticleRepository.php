<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByAuthorName(string $authorName): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.author', 'u')
            ->where($qb->expr()->like(
                $qb->expr()->concat('u.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'u.lastName')),
                $qb->expr()->literal('%' . $authorName . '%')
            ));

        return $qb->getQuery()->getResult();
    }

    public function findByTitle(string $title = ''): array
    {
        $qb = $this->createQueryBuilder('a');

        if (!empty($title)) {
            $qb->andWhere($qb->expr()->like(
                $qb->expr()->lower('a.title'),
                $qb->expr()->literal('%' . strtolower($title) . '%')
            ));
        }

        return $qb->getQuery()->getResult();
    }
}
