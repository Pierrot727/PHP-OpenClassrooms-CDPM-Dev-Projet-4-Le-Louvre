<?php

namespace App\Controller;

use App\Manager\DataManager;
use App\Manager\ParametersManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ParametersManager $parameters
     *
     * @param DataManager $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ParametersManager $parameters, DataManager $data)
    {
        $slides = $data->mainCarousel();
        $feedback = $data->feedback();
        $getInvolved = $data->getInvolved();
        $getInvolvedLeft = $data->getInvolvedLeft();
        $reservationAllowed = $parameters->isReservationAllowed();

        return $this->render('main/index.html.twig', [
            'reservation_allowed' => $reservationAllowed,
            'slides' => $slides,
            'feedbacks' => $feedback,
            'getInvolved' => $getInvolved,
            'getInvolvedLeft' => $getInvolvedLeft,
        ]);
    }

}