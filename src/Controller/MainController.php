<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\CommandType;
use App\Form\TicketCollectionType;
use App\Form\TicketType;
use App\Repository\CommandRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/ticket", name="ticket_buy")
     * @param Request $request
     * @param SessionInterface $session
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketBuy(Request $request, SessionInterface $session)
    {

        $form = $this->createForm(CommandType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = $form->getData();

            // TODO A revoir pour creer autant de ticket que demandé
            $command->addTicket(new Ticket());

            $session->set('command', $command);


            return $this->redirectToRoute('ticket_fill');
        }


        return $this->render('main/ticketBuyNew.html.twig', [
            'current_menu' => 'ticket_buy',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ticket/fill", name="ticket_fill")
     * @param Request $request
     *
     * @param SessionInterface $session
     * @param CommandRepository $commandRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketFill(Request $request, SessionInterface $session, CommandRepository $commandRepository)
    {

        $command = $session->get('command');

        $form = $this->createForm(TicketCollectionType::class, $command);
        $form->handleRequest($request);



        return $this->render('main/ticketBuyFill.html.twig', [
            'current_menu' => 'ticket_fill',
            'form' => $form->createView()
        ]);
    }
}
