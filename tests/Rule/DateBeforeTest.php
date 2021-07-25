<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\DateBefore;
use Platine\Dev\PlatineTestCase;

/**
 * DateBefore validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class DateBeforeTest extends PlatineTestCase
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



        $o = new DateBefore($param);

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
            array('1990-2-1', '1990-3-1', false),
            array('1990-2-1', '1990-3-1', false),
            array('1990-2-1', '1-1-1992', false),
            array('2020-2-1', '2020-1-31', true),
            array('2020-2-1', '2020-1-1 00:00:01', true),
            array('2011-2-1', '2011-1', true),
            array('2011-2-1', '21021991', true),
        );
    }
}
