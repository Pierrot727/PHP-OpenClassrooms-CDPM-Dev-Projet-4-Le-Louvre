<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotUnrestrictedNumber extends Constraint
{

    /*
 * Any public properties become valid options for the annotation.
 * Then, use these in your validator class.
 */
    public $message = 'Vous ne pouvez pas réserver plus de 25 tickets, contactez le service client pour davantage!';
}