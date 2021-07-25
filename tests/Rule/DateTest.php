<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Date;
use Platine\Dev\PlatineTestCase;

/**
 * Date validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class DateTest extends PlatineTestCase
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



        $o = new Date($param);

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
            array('Y-m-d', '29$', false),
            array('Y-m-d', '01-01-2019', false),
            array('Y-m-d', '2019-06-32', false),
            array('Y', '20', false),
            array('Y-m-d', '2019-10-19', true),
            array('Y', '2020', true),
            array('Ymd', '20110101', true),
        );
    }
}
