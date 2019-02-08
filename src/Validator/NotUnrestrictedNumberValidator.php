<?php

namespace App\Validator;

use App\Entity\Command;
use App\Manager\ParametersManager;
use App\Repository\CommandRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotUnrestrictedNumberValidator extends ConstraintValidator
{
    private $commandRepository;
    private $parameter;

    public function __construct(CommandRepository $commandRepository, ParametersManager $parametersManager)
    {

        $this->commandRepository = $commandRepository;
        $this->parameter = $parametersManager;
    }

    /**
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if(!$object instanceof Command){
            throw new \LogicException();
        }

        /* @var $constraint App\Validator\NotUnrestrictedNumber*/


        if($object->getNumber() > 25){

            $this->context->buildViolation($constraint->message)
                ->atPath('number')
                ->addViolation();

        }

    }
}