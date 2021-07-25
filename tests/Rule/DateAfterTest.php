<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\DateAfter;
use Platine\Dev\PlatineTestCase;

/**
 * DateAfter validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class DateAfterTest extends PlatineTestCase
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



        $o = new DateAfter($param);

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
            array('1990-2-1', '1990-2-1', false),
            array('1990-2-1', '1990-1-2', false),
            array('1990-2-1', '1-1-1990', false),
            array('2020-2-1', '2020-2-31', true),
            array('2020-2-1', '2020-2-1 00:00:01', true),
            array('2011-2-1', '2011-3', true),
            array('2011-2-1', '2022010', true),
        );
    }
}
