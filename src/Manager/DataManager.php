<?php

namespace App\Manager;


use App\Repository\DataCarouselRepository;
use App\Repository\DataRepository;

class DataManager
{
    private $carousel;
    private $dataCarousel;
    private $dataRepo;
    private $data;

    public function __construct(DataCarouselRepository $dataCarouselRepository, DataRepository $dataRepository)
    {
        $this->carousel = $dataCarouselRepository;
        $this->dataCarousel = $this->carousel->findAll();
        $this->dataRepo = $dataRepository;
    }

    public function mainCarousel() {
        return $this->carousel->findBy(['type' => 'mainCarousel']);
    }

    public function feedback() {
        return $this->carousel->findBy(['type' => 'feedback']);
    }

    public function getInvolved() {
        return $this->dataRepo->findOneBy(['position' => 'indexIntro']);
    }

    public function getInvolvedLeft() {
        return $this->dataRepo->findOneBy(['position' => 'indexPart1']);
    }
}