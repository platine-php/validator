<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\IPv4;
use Platine\PlatineTestCase;

/**
 * IPv4 validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class IPv4Test extends PlatineTestCase
{

    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $value
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($value, $expectedResult): void
    {
        $field = 'name';

        $validator = $this->getMockBuilder(Validator::class)
                ->getMock();

        $validator->expects($this->any())
                ->method('getLabel')
                ->with($field)
                ->will($this->returnValue($field));



        $o = new IPv4();

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
            array('1.1.1', false),
            array('q::2', false),
            array('2006::2:m', false),
            array('192.168.0.256', false),
            array('1.1.1.1', true),
            array('0.0.0.0', true),
            array('127.0.0.1', true),
            array('255.255.255.255', true),
        );
    }
}
