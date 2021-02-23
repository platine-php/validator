<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\Matches;
use Platine\PlatineTestCase;

/**
 * Matches validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class MatchesTest extends PlatineTestCase
{

    /**
     * test Validate method
     *
     * @dataProvider validationDataProvider
     *
     * @param  mixed $param
     * @param  mixed $value
     * @param  mixed $value2
     * @param  mixed $expectedResult
     * @return void
     */
    public function testValidate($param, $value, $value2, $expectedResult): void
    {
        $field = 'name';


        $validator = $this->getMockBuilder(Validator::class)
                ->getMock();

        $labelValuesMap = array(
            array($field, $field),
            array($param, $param)
        );

        $validator->expects($this->any())
                ->method('getLabel')
                ->will($this->returnValueMap($labelValuesMap));

        $validator->expects($this->any())
                ->method('getData')
                ->with($param)
                ->will($this->returnValue($value2));



        $o = new Matches($param);

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
            array('age', 'no', 'yes', false),
            array('age', 'yes', 'yes', true),
        );
    }
}
