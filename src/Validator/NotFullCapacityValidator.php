<?php

namespace App\Validator;

use App\Repository\CommandRepository;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotFullCapacityValidator extends ConstraintValidator
{

    /**
     * @var CommandRepository
     */
    private $commandRepository;

    public function __construct(CommandRepository $commandRepository)
    {

        $this->commandRepository = $commandRepository;
    }

    public function validate($object, Constraint $constraint)
    {
        //$selectedDate = $object->getDate();
        $selectedDay = $this->commandRepository->findOneBy(['date' => $object->getDate()]) ;

        $ticketAlreadySell = $object->getNumber();
        $availableTickets = 1000 - $ticketAlreadySell;

        /* @var $constraint App\Validator\NotFullCapacity */


        if($object->getNumber() > $availableTickets){

            $this->context->buildViolation($constraint->message)
                ->atPath('number')
                ->addViolation();

        }

    }


}
