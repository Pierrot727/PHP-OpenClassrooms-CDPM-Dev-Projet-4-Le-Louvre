<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Not01MayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\Not01May */

        if(($value->format('m') == 05) && ($value->format('d') == 01 )){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
