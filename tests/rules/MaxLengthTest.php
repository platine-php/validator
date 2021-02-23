<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\MaxLength;
use Platine\PlatineTestCase;

/**
 * MaxLength validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class MaxLengthTest extends PlatineTestCase
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



        $o = new MaxLength($param);

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
            array(2, '676', false),
            array(5, '123456', false),
            array(1, '10.0', false),
            array(2, 'ABC', false),
            array(2, '111', false),
            array(1, '01', false),
            array(2, '10', true),
            array(1, 'a', true),
            array(1, '', true),
        );
    }
}
