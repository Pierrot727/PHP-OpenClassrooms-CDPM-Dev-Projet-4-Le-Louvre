<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotFullCapacity extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
   // public $message = 'Il reste "{{ value }}" place(s) disponible.';
    public $message = 'Plus de place au jour selectionné';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
