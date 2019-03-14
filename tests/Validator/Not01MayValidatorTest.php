<?php

namespace Tests\AppBundle\Validator\Constraints;

use App\Validator\Not01May;
use App\Validator\Not01MayValidator;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class PhoneNumberValidatorTest.
 */
class DateValidatorTest extends ValidatorTestAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function getValidatorInstance()
    {
        return new Not01MayValidator();
    }

    public function testValidationOk()
    {
        $Not01MayConstraint = new Date();
        $Not01MayValidator = $this->initValidator();

        $Not01MayValidator->validate('2018-10-10', $Not01MayConstraint);
    }
}