<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    private  $logger;

    public function __construct(RegistryInterface $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Command::class);
        $this->logger = $logger;

    }

    public function countTickets(?\DateTimeInterface $date)
    {
        // Equivalent au code SQL= SELECT COUNT(date) FROM command WHERE date="2020-12-28"

        try {
            return $this->createQueryBuilder('c')
                ->select('sum(c.number)')
                ->andWhere('c.date = :val')
                ->setParameter('val', $date)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            $this->logger->error('Erreur requéte countTickets');
        }
    }


}
