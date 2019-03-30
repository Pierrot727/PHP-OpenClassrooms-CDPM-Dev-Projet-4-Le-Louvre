<?php

namespace Tests\Validator;

use App\Validator\Not01MayValidator;
use Symfony\Component\Validator\Constraints\Date;

class Not01MayValidatorTest extends ValidatorTestAbstract
{
    public function dataOk()
    {
        return [
            [new \DateTime('2018-05-02')],
            [new \DateTime('2019-05-02')],
            [new \DateTime('2020-05-03')],
            [new \DateTime('2018-04-01')],
            [new \DateTime('2018-06-01')]
        ];
    }

    public function dataKo()
    {
        return [
            [new \DateTime('2018-05-01')],
            [new \DateTime('2019-05-01')],
            [new \DateTime('2020-05-01')],
            [new \DateTime('2021-05-01')],
            [new \DateTime('2022-05-01')]
        ];
    }

    /** @dataProvider dataOk */
    public function testValidationOk($date)
    {
        $Not01MayConstraint = new Date();
        $Not01MayValidator = $this->initValidator();

        $Not01MayValidator->validate($date, $Not01MayConstraint);
    }

    /** @dataProvider dataKo */
    public function testValidationKo($date)
    {
        $Not01MayConstraint = new Date();

        $Not01MayValidator = $this->initValidator($Not01MayConstraint->message);
        $Not01MayValidator->validate($date, $Not01MayConstraint);

    }

    /**
     * {@inheritdoc}
     */
    protected function getValidatorInstance()
    {
        return new Not01MayValidator();
    }
}