<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotPublicHoliday extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    // public $message = 'Il reste "{{ value }}" place(s) disponible.';
    public $message = 'Désolé, il s\'agit d\'un jour férié';

}