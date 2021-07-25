<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\ExactLength;
use Platine\Dev\PlatineTestCase;

/**
 * ExactLength validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class ExactLengthTest extends PlatineTestCase
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



        $o = new ExactLength($param);

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
            array(2, ' ', false),
            array(10, '10', false),
            array(10, 10.0, false),
            array(2, 'A', false),
            array(2, 1, false),
            array(6, '01', false),
            array(2, '10', true),
            array(1, 'a', true),
            array(1, '', true),
        );
    }
}
