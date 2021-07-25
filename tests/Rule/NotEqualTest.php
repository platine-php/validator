<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\NotEqual;
use Platine\Dev\PlatineTestCase;

/**
 * NotEqual validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class NotEqualTest extends PlatineTestCase
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



        $o = new NotEqual($param);

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
            array(0, 0, false),
            array(10, 10, false),
            array(10.0, 10.0, false),
            array('a', 'a', false),
            array(true, true, false),
            array(false, false, false),
            array(10, 10.1, true),
            array('a', 'A', true),
        );
    }
}
