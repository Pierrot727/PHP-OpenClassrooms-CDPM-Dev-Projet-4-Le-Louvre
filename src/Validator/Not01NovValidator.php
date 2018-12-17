<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Not01NovValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\Not01Nov */

        if (($value->format('m') == 11) && ($value->format('d') == 01)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
