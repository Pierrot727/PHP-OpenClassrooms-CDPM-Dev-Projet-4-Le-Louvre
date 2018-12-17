<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParametersRepository")
 */
class Parameters
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $halfDayTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSecretKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripePublicKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailCommand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailSupport;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reservationAllowed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxTicketsPerDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHalDayTime(): ?\DateTimeInterface
    {
        return $this->halfDayTime;
    }

    public function setHalfDayTime(\DateTimeInterface $halfDayTime): self
    {
        $this->halfDayTime = $halfDayTime;

        return $this;
    }

    public function getStripeSecretKey(): ?string
    {
        return $this->stripeSecretKey;
    }

    public function setStripeSecretKey(?string $stripeSecretKey): self
    {
        $this->stripeSecretKey = $stripeSecretKey;

        return $this;
    }

    public function getStripePublicKey(): ?string
    {
        return $this->stripePublicKey;
    }

    public function setStripePublicKey(?string $stripePublicKey): self
    {
        $this->stripePublicKey = $stripePublicKey;

        return $this;
    }

    public function getEmailCommand(): ?string
    {
        return $this->emailCommand;
    }

    public function setEmailCommand(?string $emailCommand): self
    {
        $this->emailCommand = $emailCommand;

        return $this;
    }

    public function getEmailSupport(): ?string
    {
        return $this->emailSupport;
    }

    public function setEmailSupport(?string $emailSupport): self
    {
        $this->emailSupport = $emailSupport;

        return $this;
    }

    public function getReservationAllowed(): ?bool
    {
        return $this->reservationAllowed;
    }

    public function setReservationAllowed(?bool $reservationAllowed): self
    {
        $this->reservationAllowed = $reservationAllowed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxTicketsPerDay()
    {
        return $this->maxTicketsPerDay;
    }

    /**
     * @param mixed $maxTicketsPerDay
     */
    public function setMaxTicketsPerDay($maxTicketsPerDay): void
    {
        $this->maxTicketsPerDay = $maxTicketsPerDay;
    }
}
