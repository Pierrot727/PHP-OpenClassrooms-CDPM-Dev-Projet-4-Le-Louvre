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
        $this->data = $this->dataRepo->findOneBy([]);
    }

    public function mainCarousel() {
        return $this->carousel->findBy(['type' => 'mainCarousel']);
    }
}