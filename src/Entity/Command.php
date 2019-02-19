<?php

namespace App\Entity;

use App\Repository\ParametersRepository;
use App\Validator as LouvreAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 * @LouvreAssert\NotFullCapacity()
 * @LouvreAssert\NotAfter14hToday()
 */
class Command
{
    const DURATION_HALF_DAY = true;
    const DURATION_FULL_DAY = false;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\GreaterThanOrEqual("today")
     * @Assert\NotNull()
     * @LouvreAssert\NotPublicHoliday()
     * @LouvreAssert\NotTuesday()
     * @LouvreAssert\NotSunday()
     * @LouvreAssert\Not01May()
     * @LouvreAssert\Not01Nov()
     * @LouvreAssert\Not25Dec()
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     * @LouvreAssert\NotUnrestrictedNumber()
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="command", orphanRemoval=true, cascade={"persist"})
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?bool
    {
        return $this->duration;
    }

    public function setDuration(bool $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode($code): self
    {

        $this->code = $code;
    }

    public function generateCode(): self
    {
        if (!$this->code) {
            $dictionary = "0123456789";
            /** @var string $dictionary */
            $codeCommand = substr(str_shuffle($dictionary), 0, 9);
            $this->code = $codeCommand;
        }
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCommand($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getCommand() === $this) {
                $ticket->setCommand(null);
            }
        }

        return $this;
    }

    public function checkTime($time, ParametersRepository $parametersRepository)
    {

        $halfdaytime = $parametersRepository->findOneBy([]);


        if ($time < $halfdaytime) {
            return false;
        } else {
            return true;
        }

    }
}
