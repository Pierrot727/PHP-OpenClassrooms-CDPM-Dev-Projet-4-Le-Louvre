<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandType;
use App\Form\TicketCollectionType;
use App\Manager\CommandManager;
use App\Manager\MailerManager;
use App\Manager\ParametersManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command_new")
     * @param Request $request
     *
     * @param CommandManager $commandManager
     * @param ParametersManager $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandNew(Request $request, CommandManager $commandManager, ParametersManager $parameters)
    {
        $command = $commandManager->initCommand();
        $reservationAllowed = $parameters->isReservationAllowed();

        if ($reservationAllowed) {
            $form = $this->createForm(CommandType::class, $command);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $commandManager->generateTicket($command);


                return $this->redirectToRoute('command_fillTickets');
            }

            return $this->render('command/step1CreateCommand.html.twig', [
                'current_menu' => 'command_new',
                'form' => $form->createView(),
                'reservation_allowed' => $reservationAllowed
            ]);
        } else {
            return $this->render('error412.html.twig');
        }
    }

    /**
     * @Route("/command/fillTickets", name="command_fillTickets")
     * @param Request $request
     *
     * @param ParametersManager $parameters
     * @param CommandManager $commandManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commandFillTickets(Request $request, ParametersManager $parameters, CommandManager $commandManager)
    {
        $reservationAllowed = $parameters->isReservationAllowed();
        $command = $commandManager->getCurrentCommand();

        $form = $this->createForm(TicketCollectionType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandManager->priceGenerator($command);
            return $this->redirectToRoute('command_checkout');
        }
        return $this->render('command/step2FillTickets.html.twig', [
            'current_menu' => 'command_fillTickets',
            'reservation_allowed' => $reservationAllowed,
            'command' => $command,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/command/checkout", name="command_checkout")
     * @param Request $request
     * @param SessionInterface $session
     * @param ParametersManager $parameters
     *
     * @param CommandManager $commandManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function commandCheckOut(Request $request, SessionInterface $session, ParametersManager $parameters, CommandManager $commandManager)
    {
        $reservationAllowed = $parameters->isReservationAllowed();
        /** @var Command $command */
        $command = $commandManager->getCurrentCommand();


        if ($request->isMethod('POST')) {
            try {
                $commandManager->payment($command);
                return $this->redirectToRoute("command_success");
            } catch (\Exception $e) {
                $this->addFlash("danger","erreur stripe");
            }
        }

        return $this->render('command/step3CheckOut.html.twig', [
            'current_menu' => 'command_checkout',
            'reservation_allowed' => $reservationAllowed,
            'command' => $command,
            'stripe_public_key' => $parameters->getStripePublicKey(),
        ]);
    }

    /**
     * @Route("/command/success", name="command_success")
     * @param ParametersManager $parameters
     *
     * @param CommandManager $commandManager
     *
     * @param MailerManager $mailerManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success(ParametersManager $parameters, CommandManager $commandManager, MailerManager $mailerManager)
    {
        $reservationAllowed = $parameters->isReservationAllowed();

        $command = $commandManager->getCurrentCommand();

        $commandManager->recordSuccessfulCommand($command);
        $mailerManager->sendConfirmationMail($command);


        return $this->render('command/step4Success.html.twig', [
            'current_menu' => 'command_success',
            'reservation_allowed' => $reservationAllowed
        ]);
    }

}
