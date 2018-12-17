<?php

namespace App\Manager;

use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandManager {
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, CommandRepository $commandRepository)
    {
        $this->manager = $manager;
        $this->commandRepository = $commandRepository;
    }

    public function commandTotal($form) {
        //TODO: fonction qui fait le total de la commande
    }


}