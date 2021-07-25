<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Range;
use Platine\Dev\PlatineTestCase;

/**
 * Range validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class RangeTest extends PlatineTestCase
{

    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $min
     * @param  mixed $max
     * @param  mixed $value
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($min, $max, $value, $expectedResult): void
    {
        $field = 'name';


        $validator = $this->getMockInstance(Validator::class, [
            'getLabel' => $field,
            'translate' => $field,
        ]);



        $o = new Range($min, $max);

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
            array(1, 0, '-1', false),
            array(15, 20, '11', false),
            array(10.02, 11, 10.01, false),
            array('a', 'c', 'd', false),
            array(9.99, 10, 10, true),
            array('a', 'g', 'b', true),
            array('a', 'w', 't', true),
            array(-10, 1, 0, true), //ascii order
            array(0, 0.99, 0.1, true), //when value is empty
        );
    }
}
