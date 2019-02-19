<?php

namespace App\Validator;

use App\Entity\Command;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotUnrestrictedNumberValidator extends ConstraintValidator
{
    public const LIMITEDTICKETNUMBER=25;


    /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\NotUnrestrictedNumber*/


        if($value > self::LIMITEDTICKETNUMBER){

            $this->context->buildViolation($constraint->message)
                ->atPath('number')
                ->addViolation();

        }

    }
}