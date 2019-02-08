<?php

namespace App\Manager;

use App\Bridge\StripeBridge;
use App\Entity\Command;
use App\Entity\Ticket;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandManager
{
    const COMMAND_SESSION_ID = 'command';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PriceRepository
     */
    private $priceRepository;
    private $session;
    /**
     * @var StripeBridge
     */
    private $stripeBridge;
    /**
     * @var MailerManager
     */
    private $mailerManager;


    /**
     * CommandManager constructor.
     *
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     * @param StripeBridge $stripeBridge
     * @param PriceRepository $priceRepository
     * @param MailerManager $mailerManager
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, StripeBridge $stripeBridge, PriceRepository $priceRepository, MailerManager $mailerManager)
    {
        $this->session = $session;

        $this->stripeBridge = $stripeBridge;
        $this->priceRepository = $priceRepository;
        $this->mailerManager = $mailerManager;
        $this->entityManager = $entityManager;
    }

    public function initCommand()
    {
        $command = new Command();
        $this->session = $this->session->set(self::COMMAND_SESSION_ID, $command);

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
                throw new \Exception("Error priceGenerator " . $e->getMessage());
            }

            $ticket->setPrice($ticketPrice);
            $total += $ticketPrice;
        }
        $command->setPrice($total);
        return $total;
    }


    /**
     * @param Command $command
     *
     * @throws \Exception
     */
    public function payment(Command $command){
        if($this->stripeBridge->pay('Votre commnande louvre', $command->getPrice())){


            $command->generateCode();
            //$this->mailerManager->mailTo("pe.laporte@gmail.com", $command->getEmail(), "Votre commande de billet du musée du Louvre", "Toto");

            // enregistrement en bdd
        }else{
            throw new \Exception();
        }
    }

    public function generateTicket(Command $command)
    {

        while ($command->getNumber() != $command->getTickets()->count()) {
            if ($command->getNumber() > $command->getTickets()->count()) {
                $command->addTicket(new Ticket());
            }
            if ($command->getNumber() < $command->getTickets()->count()) {
                $command->removeTicket($command->getTickets()->last());
            }
        }
    }

    public function getCurrentCommand()
    {
        $session = $this->session;
        return $command = $session->get('command');
    }


    public function recordSuccessfulCommand(Command $command)
    {
        $this->entityManager->persist($command);
        $this->entityManager->flush();
    }


}