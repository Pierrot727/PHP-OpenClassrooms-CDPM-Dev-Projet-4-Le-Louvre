<?php

namespace App\Manager;

use App\Entity\Command;
use App\Repository\CommandRepository;
use App\Repository\ParametersRepository;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var PriceRepository
     */
    private $priceRepository;

    public function __construct(SessionInterface$session, EntityManagerInterface $manager, CommandRepository $commandRepository, ParametersRepository $parametersRepository, PriceRepository $priceRepository)
    {
        $this->session = $session;
        $this->manager = $manager;
        $this->command = $commandRepository;
        $this->parameter = $parametersRepository;

        $this->priceRepository = $priceRepository;
    }

    public function initCommand ()
    {
        $command = new Command();
        $session = $this->session->get('command');
        if ($session) {
            $this->session->clear();
        }
        return $command;
    }

    /**
     * @param Command $command
     *
     * @return float|int
     * @throws \Exception
     */
    public function priceGenerator(Command $command)
    {
        $total = 0;
        foreach ($command->getTickets() as $ticket) {
            $age = $ticket->getAge();
            $proof = $ticket->getReduction();

            try {
                $ticketPrice = $this->priceRepository->adjustPrice($age, $proof);
            } catch (NoResultException|NonUniqueResultException $e) {
                throw new \Exception("Error priceGenerator ".$e->getMessage());
            }

            $ticket->setPrice($ticketPrice);
            $total += $ticketPrice;
        }
        $command->setPrice($total);
        return $total;
    }


}