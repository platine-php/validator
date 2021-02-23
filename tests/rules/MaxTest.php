<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Max;
use Platine\PlatineTestCase;

/**
 * Max validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class MaxTest extends PlatineTestCase
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


        $validator = $this->getMockBuilder(Validator::class)
                ->getMock();

        $validator->expects($this->any())
                ->method('getLabel')
                ->with($field)
                ->will($this->returnValue($field));



        $o = new Max($param);

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
            array(-1, '1', false),
            array(10, '11', false),
            array(10, 10.01, false),
            array(10, 10, true),
            array('a', 'a', true),
            array('z', 'a', true),
            array('a', 'A', true), //ascii order
            array('a', '', true), //when value is empty
        );
    }
}
