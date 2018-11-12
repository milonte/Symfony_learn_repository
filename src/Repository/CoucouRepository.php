<?php

namespace App\Repository;

use App\Entity\Coucou;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Coucou|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coucou|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coucou[]    findAll()
 * @method Coucou[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoucouRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Coucou::class);
    }

    // /**
    //  * @return Coucou[] Returns an array of Coucou objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Coucou
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
