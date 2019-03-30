<?php

namespace Tests\Validator;


use App\Validator\Not25DecValidator;
use Symfony\Component\Validator\Constraints\Date;

class Not25DecValidatorTestt extends ValidatorTestAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function getValidatorInstance()
    {
        return new Not25DecValidator();
    }

    public function testValidationOk()
    {
        $Not25DecConstraint = new Date();
        $Not25DecValidator = $this->initValidator();


        $Not25DecValidator->validate(new \DateTime('2018-12-26'), $Not25DecConstraint);
        $Not25DecValidator->validate(new \DateTime('2019-11-25'), $Not25DecConstraint);
        $Not25DecValidator->validate(new \DateTime('2020-11-25'), $Not25DecConstraint);
        $Not25DecValidator->validate(new \DateTime('2018-10-23'), $Not25DecConstraint);
        $Not25DecValidator->validate(new \DateTime('2018-12-22'), $Not25DecConstraint);
    }

    public function testValidationKo()
    {
        $Not25DecConstraint = new Date();

        $Not25DecValidator = $this->initValidator($Not25DecConstraint->message);
        $Not25DecValidator->validate(new \DateTime('2018-12-25'), $Not25DecConstraint);

    }
}