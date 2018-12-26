<?php

namespace App\Manager;

use App\Entity\Parameters;
use App\Repository\ParametersRepository;
use Doctrine\ORM\EntityManagerInterface;

class ParametersManager
{
    const RESERVATION_ALLOWED = TRUE;
    const RESERVATION_NOT_ALLOWED = FALSE;

    private $parameters;

    private $values;
    private $stripeSecretKey;
    private $stripePublicKey;

    public function __construct(ParametersRepository $parametersRepository, $stripeSecretKey, $stripePublicKey)
    {
        $this->parameters = $parametersRepository;
        $this->values = $this->parameters->findOneBy([]);
        $this->stripeSecretKey = $stripeSecretKey;
        $this->stripePublicKey = $stripePublicKey;
    }

    public function getDailyCapacity()
    {
        $dailyAvailableCapacity = $this->values->getMaxTicketsPerDay();
        return $dailyAvailableCapacity;
    }

    public function isReservationAllowed()
    {
        return $this->values->getReservationAllowed() == self::RESERVATION_ALLOWED;
    }

    public function getHalfDayTime ()
    {
        $halfDayTime = $this->values->getHalDayTime();
        return $halfDayTime;
    }

    public function getDiscount() {

        return 0;
    }

    public function getStripeSecretKey() {

        if ($this->stripeSecretKey == null) {
            $this->stripeSecretKey =  $this->values->getStripeSecretKey();
        }
        return $this->stripeSecretKey;
    }

    public function getStripePublicKey() {

        if ($this->stripePublicKey == null) {
            $this->stripePublicKey = $this->values->getStripePublicKey();
        }
        return $this->stripePublicKey;
    }
}