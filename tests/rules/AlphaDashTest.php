<?php

declare(strict_types=1);

namespace Platine\Test\Validator\Rule;

use Platine\Validator\Validator;
use Platine\Validator\Rule\AlphaDash;
use Platine\PlatineTestCase;

/**
 * AlphaDash validation rule class tests
 *
 * @group core
 * @group validator
 * @group rule
 */
class AlphaDashTest extends PlatineTestCase
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



        $o = new AlphaDash();

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
            array('29', false),
            array('qbc1', false),
            array('a b', false),
            array('abc', true),
            array('éîû', true),
            array('a-b', true),
            array('alpha_dash', true),
            array('', true),
        );
    }
}
