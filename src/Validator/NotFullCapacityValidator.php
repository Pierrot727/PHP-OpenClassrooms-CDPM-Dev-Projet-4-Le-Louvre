<?php

namespace App\Validator;

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

    public function __construct(CommandRepository $commandRepository, ParametersManager $parametersManager)
    {

        $this->commandRepository = $commandRepository;
        $this->parameter = $parametersManager;
    }

    public function validate($object, Constraint $constraint)
    {
       // $test = new \DateTime('2022-12-25 14:43:13');
       // $testValue = $this->commandRepository->findOneBy(['date' => $test->format('d/m/y')]);

        $selectedDay = $this->commandRepository->findOneBy(['date' => $object]) ;
        $dailyCapacity = $this->parameter->getDailyCapacity();



        $dailyCapacity = $this->parameter->getDailyCapacity();



        /* @var $constraint App\Validator\NotFullCapacity */


        if($object->getNumber() > 1000){

            $this->context->buildViolation($constraint->message)
                ->atPath('number')
                ->addViolation();

        }

    }


}
