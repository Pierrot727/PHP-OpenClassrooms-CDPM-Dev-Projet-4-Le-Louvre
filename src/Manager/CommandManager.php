<?php

namespace App\Manager;

use App\Entity\Command;
use App\Entity\Ticket;
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
    const COMMAND_SESSION_ID = 'command';
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var PriceRepository
     */
    private $priceRepository;
    private $session;
    private $command;
    private $parameter;

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
        $this->session = $this->session->set(self::COMMAND_SESSION_ID,$command);

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

    public function generateTicket($command){
        for ($nbTickets = 1; $nbTickets <= $command->getNumber(); $nbTickets++) {
            $command->addTicket(new Ticket());
        }

    }

    public function getCurrentCommand() {
        $session = $this->session;
        return $command = $session->get('command');
    }

    public function setCommandInSession($command){
        $session = $this->session;
        return $session->set('command', $command);
    }

    public function recordSuccessfulCommand($command){

    }

}