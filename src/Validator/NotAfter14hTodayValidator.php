<?php

namespace App\Validator;


use App\Entity\Command;
use App\Repository\ParametersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotAfter14hTodayValidator extends ConstraintValidator
{
    private $entityManager;
    private $parameters;
    public function __construct(EntityManagerInterface $entityManager, ParametersRepository $parametersRepository)
    {
        $this->entityManager = $entityManager;
        $this->parameters = $parametersRepository;
    }

    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint App\Validator\NotAfter14hToday */
        $parameters = $this->parameters->findOneBy([]);
        $halfDayTime = $parameters->getHalDayTime();
        $now = \DateTime::createFromFormat('U', (string)time());

        if (!$object instanceof Command) {
            return;
        }

        if ($object->getDate()->format('Y-m-d') == $now->format('Y-m-d') &&
            $now->format('H') >= $halfDayTime->format('H') &&
            $object->getDuration() == Command::DURATION_HALF_DAY
        ) {
            $this->context->buildViolation($constraint->message)
                ->atPath('date')
                ->addViolation();
        }

    }
}
