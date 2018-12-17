<?php

namespace App\Validator;


use App\Repository\ParametersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotAfter14hTodayValidator extends ConstraintValidator
{

    public function __construct(EntityManagerInterface $manager, ParametersRepository $parametersRepository)
    {
        $this->manager = $manager;
        $this->parameters = $parametersRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\NotAfter14hToday */

            $parameters = $this->parameters->findAll();
            $halfDayTime = $parameters->getHalDayTime();

            if($value->format('H') >= $halfDayTime)
            {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }

    }
}
