<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\InList;
use Platine\Dev\PlatineTestCase;

/**
 * InList validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class InListTest extends PlatineTestCase
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



        $o = new InList($param);

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
            array(array(2, 4, 5), '', true),
            array(array(2, 4, 5), '676', false),
            array(array(12, 10), '123456', false),
            array(array('a', 'n'), '10.0', false),
            array(array('AB', 'C'), 'ABC', false),
            array(array('2', false, 3.4), '111', false),
            array(array('2', 10, false), '10', true),
            array(array('a', 'b'), 'a', true),
            array(array(true, false), false, true),
            array(array(0, 1), '01', true),
            array(array('2', true, 3.4), '111', true),
        );
    }
}
