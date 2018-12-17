<?php

namespace App\Controller;

use App\Form\CommandType;
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

            $session->set('date', $form->getData()->getDate());
            $session->set('ticketNumber', $form->getData()->getNumber());
            $session->set('duration', $form->getData()->getDuration());

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
     * @param CommandRepository $commandRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketFill(Request $request, SessionInterface $session, CommandRepository $commandRepository)
    {
        //TODO recuperer depuis la session (voir session symfony doc)
        $ticketsRequested = $session->get('ticketNumber');
        $durationRequested = $session->get('duration');
        $dateRequested = $session->get('date');

        $form = $this->createForm(TicketType::class);

        if ($ticketsRequested = 2) {
            $form = $this->createForm(TicketType::class);
            $form2 = $this->createForm(TicketType::class);
                }
        $form->handleRequest($request);



        return $this->render('main/ticketBuyFill.html.twig', [
            'current_menu' => 'ticket_fill',
            'ticketsRequested' => $ticketsRequested,
            'form' => $form->createView()
        ]);
    }
}
