<?php

namespace App\Repository;

use App\Entity\DataCarousel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DataCarousel|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCarousel|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCarousel[]    findAll()
 * @method DataCarousel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCarouselRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DataCarousel::class);
    }

    // /**
    //  * @return DataCarousel[] Returns an array of DataCarousel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataCarousel
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
