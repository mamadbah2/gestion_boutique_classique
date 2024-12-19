<?php

namespace App\Repository;

use App\Dtos\ClientDto;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    public function paginateClients(int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $limit)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return new Paginator($query);
    }

    public function countClients(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByClient(ClientDto $clientDto, int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('c');
        if (!empty($clientDto->telephone)) {
            $query->andWhere('c.telephone = :telephone')->setParameter('telephone', $clientDto->telephone);
        }
        if (!empty($clientDto->surname)) {
            $query->andWhere('c.surname = :surname')->setParameter('surname', $clientDto->surname);
        }
        $query->orderBy('c.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
            return new Paginator($query);
    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
