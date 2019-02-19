<?php

namespace App\Validator;

use mysql_xdevapi\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\ConstraintValidator;

class NotPublicHolidayValidator extends ConstraintValidator
{

    public function validate($date, Constraint $constraint)
    {
        if ($this->isNotWorkable($date)) {

            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }

    }

    public function isNotWorkable($date)
    {

        if (!$date instanceof  \DateTime) {
           throw new \Exception();
        }


        $date = strtotime(date('m/d/Y', $date->format('U')));

        $year = date('Y', $date);

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date('Y', $easterDate);

        $holidays = array(
            // Dates fixes
            mktime(0, 0, 0, 1, 1, $year),  // 1er janvier
            mktime(0, 0, 0, 5, 1, $year),  // Fête du travail
            mktime(0, 0, 0, 5, 8, $year),  // Victoire des alliés
            mktime(0, 0, 0, 7, 14, $year),  // Fête nationale
            mktime(0, 0, 0, 8, 15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1, $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 25, $year),  // Noel

            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
        );

        return in_array($date, $holidays);
    }


}