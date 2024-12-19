<?php

namespace App\Repository;

use App\Entity\Dette;
use App\enum\StatusDette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dette>
 */
class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    //    /**
    //     * @return Dette[] Returns an array of Dette objects
    //     */
    public function findDettesById(int $id, int $page, string $status = "Impaye", int $limit = null): Paginator
    {
           $query = $this->createQueryBuilder('d')
               ->andWhere('d.client = :id')
               ->setParameter('id', $id);
               if ($status ==  "Impaye") {
                $query->andWhere('d.montant != d.montantVerser');
               }
               if ($status ==  "Paye") {
                $query->andWhere('d.montant = d.montantVerser');
               }
               $query->orderBy('d.id', 'DESC')
               ->setFirstResult(($page - 1) * $limit)
               ->setMaxResults($limit)
               ->getQuery()
               ->getResult();
           return new Paginator($query);
    }

    public function findAllByPaginate(int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        return new Paginator($query);
    }

    //    public function findOneBySomeField($value): ?Dette
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
