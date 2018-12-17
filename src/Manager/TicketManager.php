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

    public function ticketsFormGenerator($numberOfTickets){
        //TODO: Generation du nombre de form adapté au nombre de tickets demandés lors de la commande

        return false;
    }

    public function ticketsRevelator($form) {
        //TODO: Exploitation du retour de tableau du formulaire
    }



}