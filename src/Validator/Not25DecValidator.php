<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Not25DecValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\Not25Dec */

        if(($value->format('m') == 12) && ($value->format('d') == 25 )){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
