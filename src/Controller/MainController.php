<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Ticket;
use App\Form\CommandType;
use App\Form\TicketCollectionType;
use App\Manager\CommandManager;
use App\Manager\ParametersManager;
use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ParametersManager $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ParametersManager $parameters)
    {
        $reservationAllowed =$parameters->isReservationAllowed();
        return $this->render('main/index.html.twig', [
            'reservation_allowed' => $reservationAllowed
        ]);
    }

    /**
     * @Route("/command", name="command_new")
     * @param Request $request
     * @param SessionInterface $session
     *
     * @param CommandManager $command
     *
     * @param ParametersManager $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandNew(Request $request, SessionInterface $session, CommandManager $command, ParametersManager $parameters)
    {
        $command->initCommand();
        $reservationAllowed =$parameters->isReservationAllowed();

        if ($reservationAllowed) {
            $form = $this->createForm(CommandType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $command = $form->getData();

                for ($nbTickets = 1; $nbTickets <= $command->getNumber(); $nbTickets++) {
                    $command->addTicket(new Ticket());
                }

                $session->set('command', $command);
                return $this->redirectToRoute('command_fillTickets');
            }

        return $this->render('main/commandNew.html.twig', [
            'current_menu' => 'command_new',
            'form' => $form->createView(),
            'reservation_allowed' => $reservationAllowed
        ]);}
        else {
            return $this->render('main/commandNotOpen.html.twig');
        }
    }

    /**
     * @Route("/command/fillTickets", name="command_fillTickets")
     * @param Request $request
     *
     * @param SessionInterface $session
     * @param ParametersManager $parameters
     * @param CommandManager $commandManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commandFillTickets(Request $request, SessionInterface $session, ParametersManager $parameters, CommandManager $commandManager)
    {
        $reservationAllowed =$parameters->isReservationAllowed();
        $command = $session->get('command');

        $form = $this->createForm(TicketCollectionType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commandManager->priceGenerator($command);

            return $this->redirectToRoute('command_checkout');
        }

        return $this->render('main/commandFillTickets.html.twig', [
            'current_menu' => 'command_fillTickets',
            'reservation_allowed' => $reservationAllowed,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/command/checkout", name="command_checkout")
     */
    public function commandCheckOut(Request $request, SessionInterface $session, ParametersManager $parameters)
    {
        $reservationAllowed =$parameters->isReservationAllowed();
        /** @var Command $command */
        $command = $session->get('command');



        return $this->render('main/commandCheckOut.html.twig', [
            'discount' => 0,
            'current_menu' => 'command_checkout',
            'reservation_allowed' => $reservationAllowed,
            'command' => $command,
            'stripe_public_key' => $parameters->getStripePublicKey(),
        ]);
    }
}
