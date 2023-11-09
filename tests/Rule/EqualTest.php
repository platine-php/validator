<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Equal;
use Platine\Dev\PlatineTestCase;

/**
 * Equal validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class EqualTest extends PlatineTestCase
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



        $o = new Equal($param);

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
            array(0, '', true),
            array(10, '10', true),
            array(10, 10.0, true),
            array('a', 'A', false),
            array(true, 1, true),
            array(false, 0, true),
            array(10, 10, true),
            array('a', 'a', true),
        );
    }
}
