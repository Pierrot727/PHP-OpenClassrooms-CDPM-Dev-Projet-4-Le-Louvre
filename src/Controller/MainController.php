<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandType;
use App\Form\TicketCollectionType;
use App\Manager\CommandManager;
use App\Manager\DataManager;
use App\Manager\MailerManager;
use App\Manager\ParametersManager;
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
    public function index(ParametersManager $parameters, DataManager $data)
    {
        $slides = $data->mainCarousel();
        $reservationAllowed =$parameters->isReservationAllowed();

        return $this->render('main/index.html.twig', [
            'reservation_allowed' => $reservationAllowed,
            'slides' => $slides
        ]);
    }

    /**
     * @Route("/command", name="command_new")
     * @param Request $request
     * @param SessionInterface $session
     *
     * @param CommandManager $commandManager
     * @param ParametersManager $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandNew(Request $request, SessionInterface $session, CommandManager $commandManager, ParametersManager $parameters)
    {
        $commandManager->initCommand();
        $reservationAllowed =$parameters->isReservationAllowed();

        if ($reservationAllowed) {
            $form = $this->createForm(CommandType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $command = $form->getData();
                $commandManager->generateTicket($command);
                //$commandManager->setCommandInSession($command);
                // TODO à voir avec seb pourquoi içi ça coince
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
     * @param ParametersManager $parameters
     * @param CommandManager $commandManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commandFillTickets(Request $request, ParametersManager $parameters, CommandManager $commandManager)
    {
        $reservationAllowed =$parameters->isReservationAllowed();
        $command = $commandManager->getCurrentCommand();

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
        $reservationAllowed =$parameters->isReservationAllowed();
        /** @var Command $command */
        $command = $commandManager->getCurrentCommand();


        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($parameters->getStripeSecretKey());
            try {
                \Stripe\Charge::create(array(
                    "amount" => $command->getPrice() * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "First test charge!"
                ));
                // envoyer mail
                // enregistrer en base
                return $this->redirectToRoute("command_success");
            }catch (\Exception $e){
                // rester sur la meme page mais dire qu'il y a un pb avec stripe
            }
        }

        return $this->render('main/commandCheckOut.html.twig', [
            'discount' => 0,
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success(ParametersManager $parameters)
    {
        $reservationAllowed =$parameters->isReservationAllowed();
        return $this->render('main/confirmation.html.twig', [
            'reservation_allowed' => $reservationAllowed
        ]);
    }

    /**
     * @Route("/command/sendMail", name="command_mail")
     * @param MailerManager $mailerManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendMail(MailerManager $mailerManager) {

        $mailerManager->mailTo( "pe.laporte@gmail.com", "test@toto.com", "tesst");

        return $this->redirectToRoute('home');
    }
}
