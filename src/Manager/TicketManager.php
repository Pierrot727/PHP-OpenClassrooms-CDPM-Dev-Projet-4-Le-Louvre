<?php

namespace App\Manager;


use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, CommandRepository $commandRepository)
    {
        $this->manager = $manager;
        $this->commandRepository = $commandRepository;
    }



}