<?php

namespace App\Validator;

use App\Entity\Command;
use App\Manager\ParametersManager;
use App\Repository\CommandRepository;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotFullCapacityValidator extends ConstraintValidator
{

    /**
     * @var CommandRepository
     */
    private $commandRepository;
    private $parameter;

    public function __construct(CommandRepository $commandRepository, ParametersManager $parametersManager)
    {

        $this->commandRepository = $commandRepository;
        $this->parameter = $parametersManager;
    }

    public function validate($object, Constraint $constraint)
    {
        if(!$object instanceof Command){
            throw new \LogicException();
        }

       $nbTicketsSold = $this->commandRepository->countTickets($object->getDate()) ;
        $dailyCapacity = $this->parameter->getDailyCapacity();

        dump($nbTicketsSold, $dailyCapacity, $object);


        /* @var $constraint App\Validator\NotFullCapacity */


        if(($object->getNumber()+$nbTicketsSold) > $dailyCapacity){

            $this->context->buildViolation($constraint->message)
                ->atPath('number')
                ->addViolation();

        }

    }


}
