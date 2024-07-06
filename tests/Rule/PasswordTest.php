<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Validator\Rule\Password;
use Platine\Validator\Validator;

/**
 * Password validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class PasswordTest extends PlatineTestCase
{
    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $param
     * @param  mixed $value
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($param, $value, $expectedResult): void
    {
        $field = 'name';


        $validator = $this->getMockInstance(Validator::class, [
            'getLabel' => $field,
            'translate' => $field,
        ]);


        $o = new Password($param);

        $this->assertEquals($expectedResult, $o->validate($field, $value, $validator));

        //ERROR MESSAGE
        $this->assertNotEmpty($o->getErrorMessage($field, $value, $validator));
    }

    /**
     * Data provider for "testValidate"
     * @return array
     */
    public function validationDataProvider(): array
    {
        $strongRule = [
            'length' => 8,
            'uppercase' => true,
            'lowercase' => true,
            'number' => true,
            'special_chars' => true,
        ];

        return array(
            array([], ' ', false), // espace
            array([], '', true), // empty
            array([], '123', false), // length
            array($strongRule, '1232322E$', false), // strong rule (missing lower)
            array($strongRule, 'aderfesergS#', false), // strong rule (missing number)
            array($strongRule, 'Ab3444444', false), // strong rule (missing special)
            array($strongRule, 'j@b3444444', false), // strong rule (missing upper)
            array($strongRule, '12323aE#', true), // very strong

        );
    }
}
