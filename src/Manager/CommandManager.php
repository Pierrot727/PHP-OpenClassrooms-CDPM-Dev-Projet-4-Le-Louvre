<?php

namespace App\Manager;

use App\Entity\Command;
use App\Repository\CommandRepository;
use App\Repository\ParametersRepository;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandManager {
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, CommandRepository $commandRepository, ParametersRepository $parametersRepository, PriceRepository $priceRepository)
    {
        $this->manager = $manager;
        $this->command = $commandRepository;
        $this->price = $priceRepository;
        $this->parameter = $parametersRepository;
    }

    public function commandTotal($form) {
        //TODO: fonction qui fait le total de la commande
    }

    /**
     * @param $ticket
     * @return int
     */
    public function getPricePerTicket(int $ticket) : int
    {
        $infoPrice = $this->price->findAll();

        $qb = $this->createQueryBuilder('q')
            ->andWhere('q.age > :min_age')
            ->andWhere('q.age < :max_age')
            ->andWhere('q.proof_needed = :proof_needed')
            ->setParameter('price', $price)
            ->orderBy('p.price', 'DES')
            ->getQuery();

        return $qb->execute();

    }


}