<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Dev\PlatineTestCase;
use Platine\Validator\Rule\Enum;
use Platine\Validator\Rule\Password;
use Platine\Validator\Validator;

/**
 * Enum validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class EnumTest extends PlatineTestCase
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



        $o = new Enum($param);

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
        return array(
            array(Password::class, 'ab3', false),
            array(Password::class, 1, true),
            array(Password::class, 3, true),
            array(Password::class, '', true),
        );
    }
}
